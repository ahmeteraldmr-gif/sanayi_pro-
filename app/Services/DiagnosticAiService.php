<?php

namespace App\Services;

use App\Models\DiagnosticSession;
use App\Models\WorkOrder;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class DiagnosticAiService
{
    /**
     * Mevcut konfigürasyondan AI sağlayıcısını döndürür.
     */
    public static function getProvider(): string
    {
        return config('ai.provider', 'gemini');
    }

    /**
     * AI API'sinin yapılandırılmış olup olmadığını kontrol eder.
     */
    public static function isConfigured(): bool
    {
        $provider = static::getProvider();

        if ($provider === 'openai') {
            return !empty(config('ai.openai_key'));
        }

        return !empty(config('ai.gemini_key'));
    }

    /**
     * Ana analiz metodu — teşhis oturumu verilerini AI'ye gönderir.
     *
     * @return array{success: bool, data?: array, error?: string}
     */
    public static function analyze(DiagnosticSession $session): array
    {
        if (!static::isConfigured()) {
            return [
                'success' => false,
                'error'   => 'AI servisi yapılandırılmamış. Lütfen yönetici ile iletişime geçin.',
            ];
        }

        $prompt = static::buildPrompt($session);

        try {
            $provider = static::getProvider();

            $rawResponse = $provider === 'openai'
                ? static::callOpenAI($prompt)
                : static::callGemini($prompt);

            $parsed = static::parseAiResponse($rawResponse);

            return ['success' => true, 'data' => $parsed];

        } catch (\Illuminate\Http\Client\ConnectionException $e) {
            Log::warning('DiagnosticAI: Bağlantı hatası', ['message' => $e->getMessage()]);
            return [
                'success' => false,
                'error'   => 'AI servisine bağlanılamadı. Lütfen internet bağlantınızı kontrol edin.',
            ];
        } catch (\Illuminate\Http\Client\RequestException $e) {
            Log::warning('DiagnosticAI: API isteği hatası', ['status' => $e->response->status()]);
            return [
                'success' => false,
                'error'   => 'AI servisi şu anda yanıt vermiyor. Lütfen birkaç dakika sonra tekrar deneyin.',
            ];
        } catch (\Exception $e) {
            Log::error('DiagnosticAI: Beklenmeyen hata', ['message' => $e->getMessage()]);
            return [
                'success' => false,
                'error'   => 'Analiz sırasında bir hata oluştu. Lütfen daha sonra tekrar deneyin.',
            ];
        }
    }

    /**
     * Oturum verilerinden AI prompt'u oluşturur.
     */
    protected static function buildPrompt(DiagnosticSession $session): string
    {
        $session->loadMissing(['vehicle', 'workOrder.items', 'obdCodes']);

        $vehicle  = $session->vehicle;
        $workOrder = $session->workOrder;

        // Araç geçmişi — önceki iş emirleri
        $vehicleHistory = '';
        if ($vehicle) {
            $prevQuery = WorkOrder::where('vehicle_id', $vehicle->id);
            if ($workOrder && $workOrder->id) {
                $prevQuery->where('id', '!=', $workOrder->id);
            }

            $previousOrders = $prevQuery->with('items')
                ->latest('date')
                ->limit(5)
                ->get();

            if ($previousOrders->isNotEmpty()) {
                $vehicleHistory .= "\n\nARAÇ SERVİS GEÇMİŞİ (Geçmiş İş Emirleri):\n";
                foreach ($previousOrders as $prevOrder) {
                    $vehicleHistory .= "- Tarih: {$prevOrder->date->format('d.m.Y')}";
                    $vehicleHistory .= " | Km: " . ($prevOrder->mileage ? number_format($prevOrder->mileage, 0, ',', '.') : 'bilinmiyor');
                    $vehicleHistory .= " | Durum: {$prevOrder->status_label}\n";
                    foreach ($prevOrder->items as $item) {
                        $vehicleHistory .= "  * {$item->name} ({$item->type})\n";
                    }
                }
            }
        }

        // OBD kodları
        $obdSection = '';
        if ($session->obdCodes->isNotEmpty()) {
            $obdSection = "\nOBD HATA KODLARI:\n";
            foreach ($session->obdCodes as $obd) {
                $obdSection .= "- {$obd->code}";
                if ($obd->description) $obdSection .= ": {$obd->description}";
                if ($obd->system)      $obdSection .= " [{$obd->system}]";
                if ($obd->usta_note)   $obdSection .= " (Usta Notu: {$obd->usta_note})";
                $obdSection .= "\n";
            }
        }

        // Belirtiler
        $symptomsText = '';
        if (!empty($session->symptoms)) {
            $symptomsText = implode(', ', (array)$session->symptoms);
        }

        // Şimdiye kadar yapılan kontroller
        $checksText = '';
        if (!empty($session->checks_performed)) {
            $checksText = implode(', ', (array)$session->checks_performed);
        }

        $mileageVal = $session->mileage ?? $workOrder?->mileage ?? $vehicle?->mileage ?? 'belirtilmemiş';

        $prompt = <<<PROMPT
Sen bir uzman otomotiv arıza teşhis asistanısın. Aşağıdaki araç bilgileri, belirtiler, yapılan kontroller ve servis geçmişine göre olası arıza nedenlerini ve adım adım kontrol önerilerini analiz et.

ÖNEMLİ KURALLAR:
1. Kesin teşhis koyma — sadece "olası nedenler" ve "kontrol önerileri" sun. Nihai teşhisi ve kararı fiziki muayene yapan usta verir.
2. Her olası neden için olasılık seviyesi belirt: Yüksek / Orta / Düşük.
3. Ustanın yapabileceği pratik, somut ve ölçülebilir kontrol adımları öner.
4. Özellikle fren, direksiyon veya diğer güvenlik açısından kritik sistemlerde doğrulanmamış bir işlemi kesin çözüm olarak sunma; fiziki kontrol zorunluluğunu belirt.
5. "ÖNERİLEN KONTROL SIRASI" (recommended_sequence): Ustanın en basit ve ucuz kontrolden başlayıp karmaşık parçaya doğru izleyeceği 3 ila 5 adımlık mantıksal kontrol dizisi oluştur (örn: "1 → Akü voltajını ve şarj dinamosunu ölç", "2 → Yakıt hattı basıncını kontrol et").
6. Türkçe yaz, teknik ama usta dostu anlaşılır bir dil kullan.
7. Yanıtını SADECE geçerli JSON formatında ver, başka bir metin veya açıklama ekleme.

ARAÇ BİLGİLERİ:
- Marka / Model: {$vehicle?->brand} {$vehicle?->model}
- Model Yılı: {$vehicle?->year}
- Motor / Yakıt: {$vehicle?->engine}
- Güncel Kilometre: {$mileageVal}
- Plaka: {$vehicle?->plate}
{$vehicleHistory}

MÜŞTERİ ŞİKAYETİ:
{$session->complaint}

BİLDİRİLEN ARIZA BELİRTİLERİ:
{$symptomsText}
{$obdSection}
ŞİMDİYE KADAR YAPILAN KONTROLLER:
{$checksText}

USTANIN YAPTIĞI ÖLÇÜMLER:
{$session->measurements}

DAHA ÖNCE YAPILAN İŞLEMLER:
{$session->previous_work}

USTANIN EK NOTLARI:
{$session->usta_notes}

YANIT FORMATI (Bu JSON şemasını aynen kullan):
{
  "summary": "Genel değerlendirme ve arıza özeti (2-3 cümle)",
  "possible_causes": [
    {
      "title": "Sorun başlığı (örn: Kızdırma Sistemi Problemi)",
      "probability": "Yüksek",
      "explanation": "Bu sorunun neden olası olduğunun detaylı açıklaması",
      "checks": ["Kontrol adımı 1", "Kontrol adımı 2", "Kontrol adımı 3"]
    }
  ],
  "recommended_sequence": [
    "1 → Akü voltajını ve marş sırasındaki voltaj düşümünü ölç",
    "2 → OBD canlı verilerinden yakıt ray basıncını incele",
    "3 → Kızdırma bujisi rölesi ve buji dirençlerini test et"
  ],
  "recommended_checks": ["Genel önerilen kontrol 1", "Kontrol 2"],
  "warning": "Güvenlik veya acil dikkat edilmesi gereken uyarılar",
  "disclaimer": "AI analizi kesin teşhis değildir. Fiziksel ölçüm, test ve ustanın değerlendirmesi zorunludur."
}
PROMPT;

        return $prompt;
    }

    /**
     * Google Gemini API çağrısı.
     */
    protected static function callGemini(string $prompt): string
    {
        $apiKey = config('ai.gemini_key');
        $model  = config('ai.gemini_model', 'gemini-1.5-flash');
        $timeout = config('ai.timeout', 30);

        $response = Http::timeout($timeout)
            ->withHeaders(['Content-Type' => 'application/json'])
            ->post(
                "https://generativelanguage.googleapis.com/v1beta/models/{$model}:generateContent?key={$apiKey}",
                [
                    'contents' => [
                        [
                            'parts' => [['text' => $prompt]]
                        ]
                    ],
                    'generationConfig' => [
                        'temperature'     => 0.3,
                        'maxOutputTokens' => 2048,
                    ],
                ]
            );

        $response->throw(); // HTTP hata varsa exception fırlat

        $text = $response->json('candidates.0.content.parts.0.text', '');

        if (empty($text)) {
            throw new \RuntimeException('Gemini API boş yanıt döndürdü.');
        }

        return $text;
    }

    /**
     * OpenAI API çağrısı.
     */
    protected static function callOpenAI(string $prompt): string
    {
        $apiKey  = config('ai.openai_key');
        $model   = config('ai.openai_model', 'gpt-4o-mini');
        $timeout = config('ai.timeout', 30);

        $response = Http::timeout($timeout)
            ->withToken($apiKey)
            ->post('https://api.openai.com/v1/chat/completions', [
                'model' => $model,
                'messages' => [
                    [
                        'role'    => 'system',
                        'content' => 'Sen uzman bir otomotiv tanı asistanısın. Yanıtlarını SADECE geçerli JSON formatında ver.',
                    ],
                    ['role' => 'user', 'content' => $prompt],
                ],
                'temperature' => 0.3,
                'max_tokens'  => 2048,
            ]);

        $response->throw();

        $text = $response->json('choices.0.message.content', '');

        if (empty($text)) {
            throw new \RuntimeException('OpenAI API boş yanıt döndürdü.');
        }

        return $text;
    }

    /**
     * AI'den gelen ham metin yanıtını JSON array'e çevirir.
     * Güvenli parse — hata durumunda fallback yapı döner.
     */
    protected static function parseAiResponse(string $rawText): array
    {
        // Markdown kod bloğu varsa temizle
        $clean = preg_replace('/```(?:json)?\s*/i', '', $rawText);
        $clean = preg_replace('/```\s*$/m', '', $clean);
        $clean = trim($clean);

        // JSON decode dene
        $decoded = json_decode($clean, true);

        if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
            // Güvenlik: XSS'e karşı tüm string değerleri temizle
            return static::sanitizeArray($decoded);
        }

        // JSON parse başarısız — ham metni yapılandırılmış formata çevir
        Log::warning('DiagnosticAI: JSON parse başarısız, ham metin döndürülüyor.');

        return [
            'summary'            => 'AI analizi tamamlandı ancak yapılandırılmış format alınamadı.',
            'possible_causes'    => [],
            'recommended_checks' => [],
            'raw_text'           => strip_tags($rawText), // XSS temizliği
            'disclaimer'         => 'Bu analiz kesin teşhis değildir. Fiziksel muayene ve ölçüm gereklidir.',
        ];
    }

    /**
     * Rekürsif XSS temizliği — tüm string değerleri htmlspecialchars ile temizler.
     */
    protected static function sanitizeArray(array $data): array
    {
        foreach ($data as $key => $value) {
            if (is_string($value)) {
                $data[$key] = htmlspecialchars($value, ENT_QUOTES | ENT_HTML5, 'UTF-8');
            } elseif (is_array($value)) {
                $data[$key] = static::sanitizeArray($value);
            }
        }
        return $data;
    }
}
