<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\BelongsToBranch;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Supplier extends Model
{
    use BelongsToBranch;

    protected $fillable = [
        'branch_id',
        'user_id',
        'name',
        'contact_person',
        'phone',
        'email',
        'address',
        'tax_office',
        'tax_no',
        'notes',
    ];

    public function parts(): HasMany
    {
        return $this->hasMany(Part::class, 'supplier', 'name');
    }
}
