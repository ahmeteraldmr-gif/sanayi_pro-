<?php

namespace App\Http\Controllers;

use App\Models\DiagnosticObdCode;
use App\Models\DiagnosticSession;
use App\Models\DiagnosticSolution;
use App\Models\Vehicle;
use App\Models\WorkOrder;
use App\Services\DiagnosticAiService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;

class DiagnosticController extends Controller
{
    /**
     * Arıza Bilgi Bankası & Geçmiş Çözümler sayfası.
     */
    public function knowledgeBase(Request $request)
    {
        $query = DiagnosticSolution::with(['solvedBy', 'workOrder']);

        if ($request->search) {
            $s = trim($request->search);
            $query->where(function ($q) use ($s) {
                $q->where('vehicle_brand', 'like', "%{$s}%")
                  ->orWhere('vehicle_model', 'like', "%{$s}%")
                  ->orWhere('root_cause', 'like', "%{$s}%")
                  ->orWhere('action_taken', 'like', "%{$s}%")
                  ->orWhere('extra_note', 'like', "%{$s}%");
            });
        }

        if ($request->brand) {
            $query->where('vehicle_brand', 'like', "%{$request->brand}%");
        }

        $solutions = $query->latest()->paginate(15)->withQueryString();

        // Bilgi bankası istatistikleri
        $totalSolutions = DiagnosticSolution::count();
        $totalSessions  = DiagnosticSession::count();
        $topBrands      = DiagnosticSolution::selectRaw('vehicle_brand, count(*) as cnt')
            ->whereNotNull('vehicle_brand')
            ->groupBy('vehicle_brand')
            ->orderByDesc('cnt')
            ->limit(5)
            ->get();

        return view('diagnostic.knowledge-base', compact(
            'solutions', 'totalSolutions', 'totalSessions', 'topBrands'
        ));
    }

    /**
     * Yeni Bağımsız Teşhis Sayfası (Arıza Bilgi Bankasından veya direkt erişim).
     */
    public function createStandalone(Request $request)
    {
        $workOrder = null;
        $vehicle   = null;

        if ($request->work_order_id) {
            $workOrder = WorkOrder::with(['vehicle.customer', 'items', 'vehicle.workOrders.items'])->find($request->work_order_id);
            if ($workOrder) {
                $vehicle = $workOrder->vehicle;
            }
        } elseif ($request->vehicle_id) {
            $vehicle = Vehicle::with(['customer', 'workOrders.items'])->find($request->vehicle_id);
            if ($vehicle) {
                $workOrder = $vehicle->workOrders()->latest('date')->first();
            }
        }

        // Araç listesi (hızlı seçim & otomatik tamamlama için)
        $vehicles = Vehicle::with('customer')->latest()->limit(50)->get();

        // Oturum kontrolü
        $session = null;
        if ($workOrder) {
            $session = DiagnosticSession::where('work_order_id', $workOrder->id)
                ->with('obdCodes')
                ->latest()
                ->first();
        } elseif ($vehicle) {
            $session = DiagnosticSession::where('vehicle_id', $vehicle->id)
                ->with('obdCodes')
                ->latest()
                ->first();
        }

        // Benzer vakalar (marka & modele göre)
        $similarCases = collect();
        if ($vehicle) {
            $similarCases = DiagnosticSolution::findSimilar(
                $vehicle->brand,
                $vehicle->model,
                $session?->symptoms ?? [],
                $session?->obdCodes->pluck('code')->toArray() ?? [],
                limit: 5
            );
        }

        return view('diagnostic.create', compact('workOrder', 'vehicle', 'vehicles', 'session', 'similarCases'));
    }

    /**
     * AJAX: Seçilen aracın tüm detaylarını, geçmiş iş emirlerini ve parçalarını getirir.
     */
    public function vehicleDetails(Vehicle $vehicle)
    {
        $vehicle->load(['customer', 'workOrders.items']);

        $lastWo = $vehicle->workOrders->sortByDesc('date')->first();
        $latestMileage = max((int)$vehicle->mileage, (int)($lastWo?->mileage ?? 0));

        // Geçmişte değiştirilen parçalar
        $pastParts = [];
        $pastOrders = [];

        foreach ($vehicle->workOrders->sortByDesc('date')->take(6) as $wo) {
            $orderItems = [];
            foreach ($wo->items as $item) {
                $orderItems[] = [
                    'name'     => $item->name,
                    'type'     => $item->type,
                    'quantity' => $item->quantity,
                ];
                if ($item->type === 'part') {
                    $pastParts[] = $item->name;
                }
            }

            $pastOrders[] = [
                'id'           => $wo->id,
                'date'         => $wo->date->format('d.m.Y'),
                'mileage'      => $wo->mileage ? number_format($wo->mileage, 0, ',', '.') : '—',
                'status_label' => $wo->status_label,
                'notes'        => $wo->notes,
                'items'        => $orderItems,
            ];
        }

        $similarCount = DiagnosticSolution::where('vehicle_brand', $vehicle->brand)
            ->where('vehicle_model', $vehicle->model)
            ->count();

        return response()->json([
            'success'       => true,
            'vehicle'       => [
                'id'        => $vehicle->id,
                'plate'     => $vehicle->plate,
                'brand'     => $vehicle->brand,
                'model'     => $vehicle->model,
                'year'      => $vehicle->year,
                'engine'    => $vehicle->engine,
                'mileage'   => $latestMileage,
                'color'     => $vehicle->color,
                'fuel_type' => $vehicle->fuel_type ?? '',
            ],
            'customer'      => $vehicle->customer ? [
                'name'  => $vehicle->customer->name,
                'phone' => $vehicle->customer->phone,
            ] : null,
            'past_orders'   => $pastOrders,
            'past_parts'    => array_values(array_unique($pastParts)),
            'similar_count' => $similarCount,
        ]);
    }

    /**
     * İş emri içerisinden teşhis formu (Geriye dönük uyumluluk).
     */
    public function create(WorkOrder $workOrder)
    {
        return $this->createStandalone(new Request(['work_order_id' => $workOrder->id]));
    }

    /**
     * Teşhis oturumunu kaydet (İş emri içi veya bağımsız).
     */
    public function store(Request $request, ?WorkOrder $workOrder = null)
    {
        return $this->storeStandalone($request, $workOrder);
    }

    /**
     * Ana Teşhis Kayıt Metodu (İş emri zorunlu değil; vehicle_id yeterli).
     */
    public function storeStandalone(Request $request, ?WorkOrder $workOrder = null)
    {
        $vehicleId = $request->vehicle_id ?: ($workOrder?->vehicle_id);

        $validated = $request->validate([
            'vehicle_id'       => $workOrder ? 'nullable|exists:vehicles,id' : 'required|exists:vehicles,id',
            'work_order_id'    => 'nullable|exists:work_orders,id',
            'mileage'          => 'nullable|numeric|min:0',
            'complaint'        => 'nullable|string|max:2000',
            'symptoms'         => 'nullable|array',
            'symptoms.*'       => 'string|max:200',
            'custom_symptom'   => 'nullable|string|max:500',
            'checks_performed' => 'nullable|array',
            'checks_performed.*' => 'string|max:200',
            'measurements'     => 'nullable|string|max:2000',
            'previous_work'    => 'nullable|string|max:2000',
            'usta_notes'       => 'nullable|string|max:2000',
            'obd_codes'        => 'nullable|array',
            'obd_codes.*.code'        => 'required|string|max:20',
            'obd_codes.*.description' => 'nullable|string|max:300',
            'obd_codes.*.system'      => 'nullable|string|max:100',
            'obd_codes.*.usta_note'   => 'nullable|string|max:500',
        ]);

        $resolvedVehicleId = $vehicleId ?: $validated['vehicle_id'];
        $resolvedWorkOrderId = $workOrder ? $workOrder->id : ($validated['work_order_id'] ?? null);

        // Belirtileri birleştir
        $symptoms = $validated['symptoms'] ?? [];
        if (!empty($validated['custom_symptom'])) {
            $symptoms[] = trim($validated['custom_symptom']);
        }

        // Oturumu bul veya oluştur
        $queryConditions = [];
        if ($resolvedWorkOrderId) {
            $queryConditions['work_order_id'] = $resolvedWorkOrderId;
        } else {
            $queryConditions['vehicle_id'] = $resolvedVehicleId;
            $queryConditions['status'] = 'draft';
        }

        $session = DiagnosticSession::updateOrCreate(
            $queryConditions,
            [
                'work_order_id'    => $resolvedWorkOrderId,
                'vehicle_id'       => $resolvedVehicleId,
                'user_id'          => auth()->id(),
                'branch_id'        => auth()->user()->branch_id,
                'mileage'          => $validated['mileage'] ?? null,
                'complaint'        => $validated['complaint'] ?? null,
                'symptoms'         => $symptoms,
                'checks_performed' => $validated['checks_performed'] ?? [],
                'measurements'     => $validated['measurements'] ?? null,
                'previous_work'    => $validated['previous_work'] ?? null,
                'usta_notes'       => $validated['usta_notes'] ?? null,
                // AI sonuçlarını sıfırla (yeniden analize hazır)
                'ai_result'        => null,
                'ai_analyzed_at'   => null,
                'feedback'         => 'pending',
                'status'           => 'draft',
            ]
        );

        // OBD kodlarını kaydet
        $session->obdCodes()->delete();
        if (!empty($validated['obd_codes'])) {
            foreach ($validated['obd_codes'] as $obd) {
                if (!empty($obd['code'])) {
                    $session->obdCodes()->create([
                        'code'        => strtoupper(trim($obd['code'])),
                        'description' => $obd['description'] ?? null,
                        'system'      => $obd['system'] ?? null,
                        'usta_note'   => $obd['usta_note'] ?? null,
                    ]);
                }
            }
        }

        return redirect()->route('diagnostic.session-analyze', $session)
            ->with('success', 'Teşhis bilgileri kaydedildi. AI analizi çalıştırılıyor...');
    }

    /**
     * İş emri içi analyze yönlendirmesi (Geriye dönük uyumluluk).
     */
    public function analyze(WorkOrder $workOrder, DiagnosticSession $session)
    {
        return $this->analyzeSession($session);
    }

    /**
     * AI Analizini Çalıştır ve Sonuç Raporunu Göster.
     */
    public function analyzeSession(DiagnosticSession $session)
    {
        // Yetki kontrolü (Multi-tenant isolation)
        if (!auth()->user()->isAdmin()) {
            if ($session->user_id && $session->user_id !== auth()->id() && (!auth()->user()->branch_id || $session->branch_id !== auth()->user()->branch_id)) {
                abort(403, 'Bu teşhis oturumuna erişim yetkiniz bulunmuyor.');
            }
        }

        $session->loadMissing(['vehicle.customer', 'workOrder.items', 'obdCodes']);
        $vehicle = $session->vehicle;
        $workOrder = $session->workOrder;

        // Analiz daha önce yapılmışsa doğrudan raporu aç
        if ($session->is_analyzed) {
            $similarCases = DiagnosticSolution::findSimilar(
                $vehicle?->brand,
                $vehicle?->model,
                $session->symptoms ?? [],
                $session->obdCodes->pluck('code')->toArray() ?? [],
                limit: 5
            );

            return view('diagnostic.result', compact('session', 'workOrder', 'vehicle', 'similarCases'));
        }

        // Rate limiting — kullanıcı başına dakikada max 5 AI isteği
        $rateLimitKey = 'ai-diagnostic:' . auth()->id();
        $maxAttempts  = config('ai.rate_limit_per_minute', 5);

        if (RateLimiter::tooManyAttempts($rateLimitKey, $maxAttempts)) {
            $seconds = RateLimiter::availableIn($rateLimitKey);
            return back()->with('error', "Çok fazla istek gönderildi. Lütfen {$seconds} saniye sonra tekrar deneyin.");
        }

        RateLimiter::hit($rateLimitKey, 60);

        // AI Analiz Servisini Çalıştır
        $result = DiagnosticAiService::analyze($session);

        if (!$result['success']) {
            if ($session->work_order_id) {
                return redirect()->route('diagnostic.create', $session->work_order_id)->with('error', $result['error']);
            }
            return redirect()->route('diagnostic.new', ['vehicle_id' => $session->vehicle_id])->with('error', $result['error']);
        }

        // Sonucu veritabanına işle
        $session->update([
            'ai_result'      => $result['data'],
            'ai_analyzed_at' => now(),
            'status'         => 'analyzed',
        ]);

        $similarCases = DiagnosticSolution::findSimilar(
            $vehicle?->brand,
            $vehicle?->model,
            $session->symptoms ?? [],
            $session->obdCodes->pluck('code')->toArray() ?? [],
            limit: 5
        );

        return view('diagnostic.result', compact('session', 'workOrder', 'vehicle', 'similarCases'));
    }

    /**
     * İş emri içi feedback (Geriye dönük uyumluluk).
     */
    public function feedback(Request $request, WorkOrder $workOrder, DiagnosticSession $session)
    {
        return $this->saveSolution($request, $session);
    }

    /**
     * Usta Çözüm Onayı ve Arıza Bilgi Bankasına Otomatik Kayıt.
     */
    public function saveSolution(Request $request, DiagnosticSession $session)
    {
        // Yetki kontrolü (Multi-tenant isolation)
        if (!auth()->user()->isAdmin()) {
            if ($session->user_id && $session->user_id !== auth()->id() && (!auth()->user()->branch_id || $session->branch_id !== auth()->user()->branch_id)) {
                abort(403, 'Bu teşhis oturumunu düzenleme yetkiniz bulunmuyor.');
            }
        }

        $validated = $request->validate([
            'root_cause'      => 'required|string|max:1000',
            'action_taken'    => 'required|string|max:1000',
            'parts_replaced'  => 'nullable',
            'extra_note'      => 'nullable|string|max:1000',
            'solution_status' => 'required|in:tamamen_cozuldu,kismen_cozuldu,devam_ediyor',
        ]);

        $session->loadMissing('vehicle');
        $vehicle = $session->vehicle;

        $session->update([
            'feedback'        => 'solved',
            'solution_status' => $validated['solution_status'],
            'status'          => 'closed',
        ]);

        // Parça listesini güvenli array'e çevir
        $partsReplaced = [];
        if (!empty($validated['parts_replaced'])) {
            if (is_array($validated['parts_replaced'])) {
                $partsReplaced = array_filter(array_map('trim', $validated['parts_replaced']));
            } else {
                $partsReplaced = array_filter(array_map('trim', explode(',', (string)$validated['parts_replaced'])));
            }
        }

        // Bilgi Bankasına Ekle (Sorun tamamen veya kısmen çözüldüyse)
        $solution = DiagnosticSolution::updateOrCreate(
            ['diagnostic_session_id' => $session->id],
            [
                'work_order_id'     => $session->work_order_id,
                'solved_by'         => auth()->id(),
                'vehicle_brand'     => $vehicle?->brand,
                'vehicle_model'     => $vehicle?->model,
                'vehicle_year'      => $vehicle?->year,
                'vehicle_engine'    => $vehicle?->engine,
                'vehicle_fuel_type' => $vehicle?->fuel_type ?? null,
                'mileage'           => $session->mileage ?? $vehicle?->mileage,
                'symptoms'          => $session->symptoms,
                'obd_codes'         => $session->obdCodes->pluck('code')->toArray(),
                'ai_suggestions'    => $session->ai_result['possible_causes'] ?? [],
                'root_cause'        => $validated['root_cause'],
                'action_taken'      => $validated['action_taken'],
                'parts_replaced'    => $partsReplaced,
                'result'            => match($validated['solution_status']) {
                    'tamamen_cozuldu' => 'Sorun tamamen çözüldü',
                    'kismen_cozuldu'  => 'Kısmen çözüldü',
                    default           => 'Sorun devam ediyor',
                },
                'solution_status'   => $validated['solution_status'],
                'extra_note'        => $validated['extra_note'] ?? null,
            ]
        );

        if ($session->work_order_id) {
            return redirect()->route('work-orders.show', $session->work_order_id)
                ->with('success', 'Arıza çözümü onaylandı ve Arıza Bilgi Bankası arşivine eklendi!');
        }

        return redirect()->route('diagnostic.knowledge-base')
            ->with('success', 'Arıza çözümü onaylandı ve Arıza Bilgi Bankası arşivine başarıyla eklendi!');
    }

    /**
     * AI Öneri Geri Bildirimi (👍 Yardımcı oldu / 👎 Yardımcı olmadı / 🔧 Sorun farklı çıktı).
     */
    public function saveAiFeedback(Request $request, DiagnosticSession $session)
    {
        // Yetki kontrolü (Multi-tenant isolation)
        if (!auth()->user()->isAdmin()) {
            if ($session->user_id && $session->user_id !== auth()->id() && (!auth()->user()->branch_id || $session->branch_id !== auth()->user()->branch_id)) {
                abort(403, 'Bu teşhis oturumuna erişim yetkiniz bulunmuyor.');
            }
        }

        $validated = $request->validate([
            'ai_feedback' => 'required|in:helpful,not_helpful,different_cause',
        ]);

        $session->update([
            'ai_feedback' => $validated['ai_feedback'],
        ]);

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success'     => true,
                'ai_feedback' => $session->ai_feedback,
                'message'     => 'Geri bildiriminiz kaydedildi.',
            ]);
        }

        return back()->with('success', 'Geri bildiriminiz için teşekkürler!');
    }
}
