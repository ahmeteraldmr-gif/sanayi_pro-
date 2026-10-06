<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'branch_id',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isManager(): bool
    {
        return in_array($this->role, ['admin', 'manager']);
    }

    public function isUsta(): bool
    {
        return in_array($this->role, ['admin', 'manager', 'usta']);
    }

    public function isCirak(): bool
    {
        return $this->role === 'cirak';
    }

    public function isAccounting(): bool
    {
        return in_array($this->role, ['admin', 'manager', 'accounting']);
    }

    public function hasRole(array $roles): bool
    {
        if ($this->isAdmin()) return true;
        return in_array($this->role, $roles);
    }

    public function getRoleLabelAttribute(): string
    {
        return match ($this->role) {
            'admin'      => 'Admin (Süper Yönetici)',
            'manager'    => 'Servis Müdürü',
            'usta'       => 'Usta',
            'cirak'      => 'Çırak',
            'accounting' => 'Muhasebe',
            default      => 'Şube Çalışanı',
        };
    }
}
