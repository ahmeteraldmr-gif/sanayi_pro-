<?php

namespace App\Models;

use App\Models\Traits\BelongsToBranch;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class DiagnosticSession extends Model
{
    use BelongsToBranch;
    protected $fillable = [
        'work_order_id', 'vehicle_id', 'user_id', 'branch_id', 'mileage',
        'complaint', 'symptoms', 'checks_performed', 'obd_codes_summary',
        'measurements', 'previous_work', 'usta_notes',
        'ai_result', 'ai_analyzed_at',
        'feedback', 'ai_feedback', 'solution_status', 'status',
    ];

    protected $casts = [
        'symptoms'          => 'array',
        'checks_performed'  => 'array',
        'obd_codes_summary' => 'array',
        'ai_result'         => 'array',
        'ai_analyzed_at'    => 'datetime',
    ];

    // ----- Relations -----

    public function workOrder(): BelongsTo
    {
        return $this->belongsTo(WorkOrder::class);
    }

    public function vehicle(): BelongsTo
    {
        return $this->belongsTo(Vehicle::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

    public function obdCodes(): HasMany
    {
        return $this->hasMany(DiagnosticObdCode::class);
    }

    public function solution(): HasOne
    {
        return $this->hasOne(DiagnosticSolution::class);
    }

    // ----- Accessors -----

    public function getIsAnalyzedAttribute(): bool
    {
        return $this->ai_analyzed_at !== null && $this->ai_result !== null;
    }

    public function getSymptomListAttribute(): array
    {
        return $this->symptoms ?? [];
    }

    /**
     * AI sonuçlarından olası nedenleri döndürür.
     */
    public function getPossibleCausesAttribute(): array
    {
        return $this->ai_result['possible_causes'] ?? [];
    }

    /**
     * AI sonuçlarından genel değerlendirmeyi döndürür.
     */
    public function getAiSummaryAttribute(): string
    {
        return $this->ai_result['summary'] ?? '';
    }

    /**
     * AI sonuçlarından önerilen kontrolleri döndürür.
     */
    public function getRecommendedChecksAttribute(): array
    {
        return $this->ai_result['recommended_checks'] ?? [];
    }

    /**
     * AI sonuçlarından önerilen kontrol sırasını döndürür.
     */
    public function getRecommendedSequenceAttribute(): array
    {
        return $this->ai_result['recommended_sequence'] ?? [];
    }

    public function getFeedbackLabelAttribute(): string
    {
        return match($this->feedback) {
            'solved'          => '✅ Çözüldü',
            'not_helpful'     => '❌ İşe Yaramadı',
            'different_cause' => '🔧 Farklı Neden Bulundu',
            default           => '⏳ Bekliyor',
        };
    }
}
