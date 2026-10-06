<?php

namespace App\Services;

use App\Models\WorkOrder;
use App\Models\WorkOrderNotification;

class NotificationService
{
    /**
     * Türkiye telefon numaralarını WhatsApp/API formatına çevirir (Örn: 905321234567).
     */
    public static function formatPhoneNumber(?string $phone): string
    {
        if (empty($phone)) return '';
        
        $clean = preg_replace('/[^0-9]/', '', $phone);
        
        if (str_starts_with($clean, '0')) {
            $clean = '90' . substr($clean, 1);
        } elseif (!str_starts_with($clean, '90') && strlen($clean) === 10) {
            $clean = '90' . $clean;
        }

        return $clean;
    }

    /**
     * İş emri ve aşama durumuna özel Türkçe müşteri bildirim mesajı üretir.
     */
    public static function getMessageForStatus(WorkOrder $workOrder, ?string $statusKey = null): string
    {
        $statusKey = $statusKey ?: $workOrder->status;
        $customerName = $workOrder->vehicle?->customer?->name ?? 'Değerli Müşterimiz';
        $plate = $workOrder->vehicle?->plate ?? '';
        $vehicleInfo = trim(($workOrder->vehicle?->brand ?? '') . ' ' . ($workOrder->vehicle?->model ?? ''));
        $orderIdStr = '#' . str_pad($workOrder->id, 5, '0', STR_PAD_LEFT);
        $totalFormatted = '₺' . number_format($workOrder->grand_total, 2, ',', '.');
        $workshopName = $workOrder->branch?->name ?? 'SanayiPro Oto Servis';

        return match($statusKey) {
            'arac_kabul', 'beklemede' => 
                "Sayın {$customerName}, {$plate} ({$vehicleInfo}) plakalı aracınız servisimize kabul edilmiştir. İş Emri: {$orderIdStr}. Bizi tercih ettiğiniz için teşekkür ederiz. {$workshopName}",

            'ariza_tespiti' => 
                "Sayın {$customerName}, {$plate} plakalı aracınızın arıza tespiti ve detaylı kontrolü yapılmaktadır. Gelişmeler aktarılacaktır. {$workshopName}",

            'islem_basladi', 'devam_ediyor' => 
                "🔧 Sayın {$customerName}, {$plate} plakalı aracınızın bakım ve tamir işlemleri başlamıştır. İş Emri: {$orderIdStr}. {$workshopName}",

            'parca_bekleniyor' => 
                "📦 Sayın {$customerName}, {$plate} plakalı aracınız için gerekli orijinal yedek parçaların tedariği beklenmektedir. Parçalar ulaşır ulaşmaz işlemler tamamlanacaktır. {$workshopName}",

            'kontrol' => 
                "🔍 Sayın {$customerName}, {$plate} plakalı aracınızın servis işlemleri bitmiş olup son güvenlik kontrolleri ve yol testi yapılmaktadır. {$workshopName}",

            'tamamlandi', 'teslim_edildi', 'odendi' => 
                "✅ Sayın {$customerName}, {$plate} ({$vehicleInfo}) plakalı aracınızın tüm servis işlemleri başarıyla tamamlanmış olup teslimata hazırdır! Toplam Tutar: {$totalFormatted}. {$workshopName}",

            default => 
                "Sayın {$customerName}, {$plate} plakalı aracınızın servis durumu güncellenmiştir: " . $workOrder->status_label . ". {$workshopName}"
        };
    }

    /**
     * Doğrudan ücretsiz 1-Tık WhatsApp Web / Mobil Gönderim Bağlantısı üretir.
     */
    public static function generateWhatsAppUrl(string $phone, string $message): string
    {
        $formattedPhone = self::formatPhoneNumber($phone);
        if (empty($formattedPhone)) return '#';

        return "https://api.whatsapp.com/send?phone={$formattedPhone}&text=" . urlencode($message);
    }

    /**
     * Müşteriye gönderilen bildirimi veritabanına kaydeder (Audit Ledger).
     */
    public static function logNotification(WorkOrder $workOrder, string $channel, string $statusKey, string $phone, string $message): WorkOrderNotification
    {
        return WorkOrderNotification::create([
            'work_order_id'   => $workOrder->id,
            'channel'         => $channel,
            'status_key'      => $statusKey,
            'recipient_phone' => self::formatPhoneNumber($phone) ?: $phone,
            'message'         => $message,
            'sent_at'         => now(),
        ]);
    }
}
