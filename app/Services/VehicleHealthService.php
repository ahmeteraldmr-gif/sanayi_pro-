<?php

namespace App\Services;

use App\Models\Vehicle;
use Carbon\Carbon;

class VehicleHealthService
{
    /**
     * Generate complete Vehicle Health Report Card (Araç Sağlık Karnesi)
     */
    public function generateHealthReport(Vehicle $vehicle): array
    {
        $vehicle->loadMissing(['workOrders.items']);

        $completedWorkOrders = $vehicle->workOrders
            ->whereIn('status', ['tamamlandi', 'devam_ediyor'])
            ->sortByDesc('date');

        $lastServiceDate = $vehicle->last_service_date;
        
        $nextServiceDate = $lastServiceDate 
            ? $lastServiceDate->copy()->addMonths(6) 
            : Carbon::today()->addMonths(6);

        $itemHistory = [];
        foreach ($completedWorkOrders as $wo) {
            foreach ($wo->items as $item) {
                $itemHistory[] = [
                    'name' => mb_strtolower($item->name, 'UTF-8'),
                    'date' => $wo->date,
                    'mileage' => $wo->mileage ?? $vehicle->mileage,
                ];
            }
        }

        // 1. Motor (Yağ, Filtre, Antifriz, Buji)
        $motorStatus = $this->evaluateComponent(
            $itemHistory,
            ['yağ', 'yag', 'yağ filtresi', 'buji', 'triger', 'antifriz', 'hava filtresi', 'yakıt filtresi'],
            6,
            12
        );

        // 2. Fren (Balata, Disk, Hidrolik)
        $frenStatus = $this->evaluateComponent(
            $itemHistory,
            ['fren', 'balata', 'disk', 'hidrolik', 'el freni', 'abs'],
            8,
            14
        );

        // 3. Akü (Akü değişimi, şarj)
        $akuStatus = $this->evaluateComponent(
            $itemHistory,
            ['akü', 'aku', 'şarj', 'stator', 'alternatör'],
            24,
            36
        );

        // 4. Lastikler (Lastik, Rot, Balans)
        $lastikStatus = $this->evaluateComponent(
            $itemHistory,
            ['lastik', 'rot', 'balans', 'subap', 'jant'],
            12,
            24
        );

        // 5. Klima (Klima gazı, polen filtresi)
        $klimaStatus = $this->evaluateComponent(
            $itemHistory,
            ['klima', 'polen', 'klima gazı', 'kompresör', 'evaporatör'],
            12,
            18
        );

        return [
            'motor' => $motorStatus,
            'fren' => $frenStatus,
            'aku' => $akuStatus,
            'lastikler' => $lastikStatus,
            'klima' => $klimaStatus,
            'last_service_date' => $lastServiceDate,
            'next_service_date' => $nextServiceDate,
            'overall_score' => $this->calculateOverallScore([$motorStatus, $frenStatus, $akuStatus, $lastikStatus, $klimaStatus]),
        ];
    }

    private function evaluateComponent(array $itemHistory, array $keywords, int $warningMonths, int $dangerMonths): array
    {
        $lastMatched = null;

        foreach ($itemHistory as $item) {
            foreach ($keywords as $kw) {
                if (str_contains($item['name'], $kw)) {
                    if (!$lastMatched || $item['date']->gt($lastMatched['date'])) {
                        $lastMatched = $item;
                    }
                }
            }
        }

        if (!$lastMatched) {
            return [
                'status' => 'warning',
                'color' => 'yellow',
                'badge' => '🟡',
                'label' => 'Yakında Bakım',
                'description' => 'Servis kaydı taranıyor, periyodik kontrol önerilir.',
                'last_serviced_at' => null,
            ];
        }

        $monthsAgo = (int) $lastMatched['date']->diffInMonths(Carbon::now());

        if ($monthsAgo <= $warningMonths) {
            return [
                'status' => 'good',
                'color' => 'green',
                'badge' => '🟢',
                'label' => 'İyi',
                'description' => $lastMatched['date']->format('d.m.Y') . ' tarihinde bakımı yapıldı.',
                'last_serviced_at' => $lastMatched['date'],
            ];
        } elseif ($monthsAgo <= $dangerMonths) {
            return [
                'status' => 'warning',
                'color' => 'yellow',
                'badge' => '🟡',
                'label' => 'Yakında Bakım',
                'description' => $monthsAgo . ' ay önce bakımı yapıldı.',
                'last_serviced_at' => $lastMatched['date'],
            ];
        } else {
            return [
                'status' => 'danger',
                'color' => 'red',
                'badge' => '🔴',
                'label' => 'Değişim Öneriliyor',
                'description' => $monthsAgo . ' aydır bakım yapılmadı.',
                'last_serviced_at' => $lastMatched['date'],
            ];
        }
    }

    private function calculateOverallScore(array $statuses): string
    {
        $dangers = count(array_filter($statuses, fn($s) => $s['status'] === 'danger'));
        $warnings = count(array_filter($statuses, fn($s) => $s['status'] === 'warning'));

        if ($dangers > 0) return 'Acil Kontrol Gerekiyor';
        if ($warnings > 2) return 'Periyodik Bakım Zamanı';
        return 'İdeal Durumda';
    }
}
