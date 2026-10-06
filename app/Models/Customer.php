<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\BelongsToBranch;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Customer extends Model
{
    use BelongsToBranch;

    protected $fillable = ['branch_id', 'user_id', 'name', 'phone', 'address', 'notes'];

    public function vehicles(): HasMany
    {
        return $this->hasMany(Vehicle::class);
    }

    public function workOrders()
    {
        return $this->hasManyThrough(WorkOrder::class, Vehicle::class);
    }
}

