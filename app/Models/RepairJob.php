<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

#[Fillable([
    'reception_id',
    'technician_id',
    'status',
    'diagnosis',
    'estimated_cost',
    'final_cost',
    'diagnosed_at',
    'quoted_at',
    'approved_at',
    'started_at',
    'completed_at',
    'notes',
])]
class RepairJob extends Model
{
    use HasFactory;

    protected $casts = [
        'estimated_cost' => 'decimal:2',
        'final_cost' => 'decimal:2',
        'diagnosed_at' => 'datetime',
        'quoted_at' => 'datetime',
        'approved_at' => 'datetime',
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    public function reception(): BelongsTo
    {
        return $this->belongsTo(Reception::class);
    }

    public function technician(): BelongsTo
    {
        return $this->belongsTo(User::class, 'technician_id');
    }

    public function steps(): HasMany
    {
        return $this->hasMany(RepairStep::class)->orderBy('performed_at', 'asc');
    }

    public function warranty(): HasOne
    {
        return $this->hasOne(Warranty::class);
    }
}
