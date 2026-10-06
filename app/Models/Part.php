<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\BelongsToBranch;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Part extends Model
{
    use BelongsToBranch;

    protected $fillable = [
        'branch_id', 'user_id', 'name', 'brand', 'code', 'oem_code', 
        'category', 'supplier', 'compatible_vehicles', 'buy_price', 
        'last_buy_price', 'sell_price', 'stock', 'min_stock', 'notes'
    ];

    protected $casts = [
        'buy_price'      => 'decimal:2',
        'last_buy_price' => 'decimal:2',
        'sell_price'     => 'decimal:2',
    ];

    public function movements(): HasMany
    {
        return $this->hasMany(PartMovement::class);
    }

    public function workOrderItems(): HasMany
    {
        return $this->hasMany(WorkOrderItem::class);
    }

    public function isLowStock(): bool
    {
        return $this->stock <= $this->min_stock;
    }

    public function getProfitAttribute(): float
    {
        return max(0, $this->sell_price - $this->buy_price);
    }

    public function getProfitMarginAttribute(): float
    {
        if ($this->sell_price <= 0) return 0;
        return round((($this->sell_price - $this->buy_price) / $this->sell_price) * 100, 1);
    }
}

