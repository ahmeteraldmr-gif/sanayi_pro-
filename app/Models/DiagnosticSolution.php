<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DiagnosticSolution extends Model
{
    protected $fillable = [
        'diagnostic_session_id', 'work_order_id', 'solved_by',
        'vehicle_brand', 'vehicle_model', 'vehicle_year', 'vehicle_engine',
        'vehicle_fuel_type', 'mileage',
        'symptoms', 'obd_codes', 'ai_suggestions',
        'root_cause', 'action_taken', 'parts_replaced', 'result',
        'solution_status', 'extra_note',
    ];

    protected $casts = [
        'symptoms'       => 'array',
        'obd_codes'      => 'array',
        'parts_replaced' => 'array',
        'ai_suggestions' => 'array',
    ];

    public function session(): BelongsTo
    {
        return $this->belongsTo(DiagnosticSession::class, 'diagnostic_session_id');
    }

    public function workOrder(): BelongsTo
    {
        return $this->belongsTo(WorkOrder::class);
    }

    public function solvedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'solved_by');
    }

    /**
     * Benzer vakaları bul — aynı marka/model + örtüşen belirtiler & OBD kodları
     */
    public static function findSimilar(
        ?string $brand,
        ?string $model,
        array $symptoms = [],
        array $obdCodes = [],
        int $limit = 5
    ): \Illuminate\Database\Eloquent\Collection {
        $query = static::with('solvedBy')
            ->when($brand, fn($q) => $q->where('vehicle_brand', 'like', "%{$brand}%"))
            ->when($model, fn($q) => $q->where('vehicle_model', 'like', "%{$model}%"))
            ->latest()
            ->limit(max($limit * 3, 20));

        $results = $query->get();

        // Eğer marka/model ile eşleşen çıkmazsa veya az çıkarsa, genel belirti eşleşmesine de bak
        if ($results->count() < $limit && (!empty($symptoms) || !empty($obdCodes))) {
            $otherResults = static::with('solvedBy')
                ->whereNotIn('id', $results->pluck('id'))
                ->latest()
                ->limit(20)
                ->get();
            $results = $results->merge($otherResults);
        }

        // Örtüşme puanına göre akıllı sıralama
        $results = $results->sortByDesc(function ($sol) use ($brand, $model, $symptoms, $obdCodes) {
            $score = 0;
            if ($brand && strcasecmp($sol->vehicle_brand ?? '', $brand) === 0) $score += 10;
            if ($model && strcasecmp($sol->vehicle_model ?? '', $model) === 0) $score += 15;

            $solSymptoms = (array)($sol->symptoms ?? []);
            if (!empty($symptoms) && !empty($solSymptoms)) {
                $score += count(array_intersect($symptoms, $solSymptoms)) * 5;
            }

            $solObds = (array)($sol->obd_codes ?? []);
            if (!empty($obdCodes) && !empty($solObds)) {
                $score += count(array_intersect($obdCodes, $solObds)) * 10;
            }

            return $score;
        });

        return $results->take($limit)->values();
    }
}
