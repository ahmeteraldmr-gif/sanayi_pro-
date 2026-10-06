<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\BelongsToBranch;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class WorkOrder extends Model
{
    use BelongsToBranch;

    protected $fillable = [
        'branch_id', 'user_id', 'vehicle_id', 'date', 'mileage', 'status',
        'total_parts', 'total_labor', 'discount', 'notes'
    ];

    protected $casts = [
        'date'        => 'date',
        'total_parts' => 'decimal:2',
        'total_labor' => 'decimal:2',
        'discount'    => 'decimal:2',
    ];

    public function vehicle(): BelongsTo
    {
        return $this->belongsTo(Vehicle::class);
    }

    public function customer(): \Illuminate\Database\Eloquent\Relations\HasOneThrough
    {
        return $this->hasOneThrough(Customer::class, Vehicle::class, 'id', 'id', 'vehicle_id', 'customer_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(WorkOrderItem::class);
    }

    public function tasks(): HasMany
    {
        return $this->hasMany(WorkOrderTask::class);
    }

    public function partMovements(): HasMany
    {
        return $this->hasMany(PartMovement::class);
    }

    public function notifications(): HasMany
    {
        return $this->hasMany(WorkOrderNotification::class);
    }

    public function diagnosticSessions(): HasMany
    {
        return $this->hasMany(DiagnosticSession::class);
    }

    public function diagnosticSolution(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(DiagnosticSolution::class);
    }

    public function getGrandTotalAttribute(): float
    {
        return (float)$this->total_parts + (float)$this->total_labor - (float)$this->discount;
    }

    public function getIsPaidAttribute(): bool
    {
        return $this->status === 'odendi';
    }

    public function getPartsTotalAttribute(): float
    {
        return (float)$this->total_parts;
    }

    public function getLaborTotalAttribute(): float
    {
        return (float)$this->total_labor;
    }

    public function getStatusStepAttribute(): int
    {
        return match($this->status) {
            'arac_kabul', 'beklemede'      => 1,
            'ariza_tespiti'                 => 2,
            'islem_basladi', 'devam_ediyor' => 3,
            'parca_bekleniyor'              => 4,
            'kontrol', 'tamamlandi'         => 5,
            'teslim_edildi', 'odendi'       => 6,
            default                         => 1,
        };
    }

    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            'arac_kabul', 'beklemede'      => 'Araç Kabul',
            'ariza_tespiti'                 => 'Arıza Tespiti',
            'islem_basladi', 'devam_ediyor' => 'İşlem Başladı',
            'parca_bekleniyor'              => 'Parça Bekleniyor',
            'kontrol', 'tamamlandi'         => 'Kontrol',
            'teslim_edildi', 'odendi'       => 'Teslim Edildi',
            default                         => $this->status,
        };
    }

    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            'arac_kabul', 'beklemede'      => 'emerald',
            'ariza_tespiti'                 => 'blue',
            'islem_basladi', 'devam_ediyor' => 'indigo',
            'parca_bekleniyor'              => 'amber',
            'kontrol', 'tamamlandi'         => 'cyan',
            'teslim_edildi', 'odendi'       => 'emerald',
            default                         => 'slate',
        };
    }

    public static function getTimelineSteps(): array
    {
        return [
            1 => ['key' => 'arac_kabul',        'label' => 'Araç Kabul',       'emoji' => '🟢', 'color' => 'emerald'],
            2 => ['key' => 'ariza_tespiti',     'label' => 'Arıza Tespiti',    'emoji' => '🔵', 'color' => 'blue'],
            3 => ['key' => 'islem_basladi',     'label' => 'İşlem Başladı',    'emoji' => '🟣', 'color' => 'indigo'],
            4 => ['key' => 'parca_bekleniyor',  'label' => 'Parça Bekleniyor', 'emoji' => '🟡', 'color' => 'amber'],
            5 => ['key' => 'kontrol',           'label' => 'Kontrol & Test',   'emoji' => '🔵', 'color' => 'cyan'],
            6 => ['key' => 'teslim_edildi',     'label' => 'Teslim Edildi',    'emoji' => '🟢', 'color' => 'emerald'],
        ];
    }

    public function recalculate(): void
    {
        $this->total_parts = $this->items()->where('type', 'part')->sum('total');
        $this->total_labor = $this->items()->where('type', 'labor')->sum('total');
        $this->save();
    }
}

