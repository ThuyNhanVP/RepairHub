<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'warranty_id',
    'user_id',
    'claim_code',
    'status',
    'issue',
    'resolution',
    'received_at',
    'resolved_at',
    'notes',
])]
class WarrantyClaim extends Model
{
    use HasFactory;

    protected $casts = [
        'received_at' => 'datetime',
        'resolved_at' => 'datetime',
    ];

    public function warranty(): BelongsTo
    {
        return $this->belongsTo(Warranty::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
