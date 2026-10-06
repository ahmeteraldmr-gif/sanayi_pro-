<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PartMovement extends Model
{
    protected $fillable = ['part_id', 'work_order_id', 'vehicle_id', 'type', 'quantity', 'unit_price', 'note'];

    protected $casts = [
        'unit_price' => 'decimal:2',
    ];

    public function part(): BelongsTo
    {
        return $this->belongsTo(Part::class);
    }

    public function workOrder(): BelongsTo
    {
        return $this->belongsTo(WorkOrder::class);
    }

    public function vehicle(): BelongsTo
    {
        return $this->belongsTo(Vehicle::class);
    }
}
