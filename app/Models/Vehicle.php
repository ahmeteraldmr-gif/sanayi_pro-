<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\BelongsToBranch;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Vehicle extends Model
{
    use BelongsToBranch;

    protected $fillable = [
        'branch_id', 'user_id', 'customer_id', 'plate', 'brand', 'model',
        'engine', 'year', 'mileage', 'color', 'notes'
    ];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function workOrders(): HasMany
    {
        return $this->hasMany(WorkOrder::class);
    }

    public function diagnosticSessions(): HasMany
    {
        return $this->hasMany(DiagnosticSession::class);
    }

    public function diagnosticSolutions(): HasMany
    {
        return $this->hasMany(DiagnosticSolution::class, 'vehicle_brand', 'brand')
                    ->where('vehicle_model', $this->model);
    }

    public function getPlateFormattedAttribute(): string
    {
        return strtoupper($this->plate);
    }

    public function getLatestMileageAttribute(): int
    {
        $maxWoMileage = $this->workOrders()->max('mileage');
        return max((int)$this->mileage, (int)$maxWoMileage);
    }

    public function getLastServiceDateAttribute()
    {
        $lastWo = $this->workOrders()->latest('date')->first();
        return $lastWo ? $lastWo->date : null;
    }
}

