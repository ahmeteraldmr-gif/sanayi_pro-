<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DiagnosticObdCode extends Model
{
    protected $fillable = [
        'diagnostic_session_id',
        'code',
        'description',
        'system',
        'usta_note',
    ];

    public function session(): BelongsTo
    {
        return $this->belongsTo(DiagnosticSession::class, 'diagnostic_session_id');
    }
}
