<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\BelongsToBranch;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Appointment extends Model
{
    use BelongsToBranch;

    protected $fillable = [
        'branch_id', 'user_id', 'vehicle_id', 'customer_id', 'work_order_id',
        'title', 'start_time', 'end_time', 'status', 'notes'
    ];

    protected $casts = [
        'start_time' => 'datetime',
        'end_time'   => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function vehicle(): BelongsTo
    {
        return $this->belongsTo(Vehicle::class);
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function workOrder(): BelongsTo
    {
        return $this->belongsTo(WorkOrder::class);
    }

    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            'randevu'    => '🟢 Randevu',
            'islemde'    => '🔵 İşlemde',
            'tamamlandi' => '✅ Tamamlandı',
            'iptal'      => '🔴 İptal',
            default      => $this->status,
        };
    }

    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            'randevu'    => 'emerald',
            'islemde'    => 'blue',
            'tamamlandi' => 'indigo',
            'iptal'      => 'rose',
            default      => 'slate',
        };
    }
}
