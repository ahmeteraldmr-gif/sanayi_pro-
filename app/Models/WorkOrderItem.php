<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WorkOrderItem extends Model
{
    protected $fillable = [
        'work_order_id', 'part_id', 'type',
        'name', 'quantity', 'unit_price', 'total'
    ];

    protected $casts = [
        'unit_price' => 'decimal:2',
        'total'      => 'decimal:2',
    ];

    public function workOrder(): BelongsTo
    {
        return $this->belongsTo(WorkOrder::class);
    }

    public function part(): BelongsTo
    {
        return $this->belongsTo(Part::class);
    }
}
