<?php

namespace App\Models\Traits;

use Illuminate\Database\Eloquent\Builder;

trait BelongsToBranch
{
    protected static function booted()
    {
        // Admin değilse sadece KENDİ (user_id) verilerini görsün
        static::addGlobalScope('user_isolation', function (Builder $builder) {
            if (auth()->check() && !auth()->user()->isAdmin()) {
                $builder->where(function ($q) {
                    $q->where('user_id', auth()->id());
                    if (auth()->user()->branch_id) {
                        $q->orWhere(function ($sub) {
                            $sub->whereNull('user_id')
                                ->where('branch_id', auth()->user()->branch_id);
                        });
                    }
                });
            }
        });

        // Yeni kayıt oluşturulurken otomatik olarak ustanın user_id ve branch_id'sini ata
        static::creating(function ($model) {
            if (auth()->check() && !auth()->user()->isAdmin()) {
                $model->user_id = auth()->id();
                $model->branch_id = auth()->user()->branch_id;
            }
        });
    }

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }

    public function branch()
    {
        return $this->belongsTo(\App\Models\Branch::class);
    }
}
