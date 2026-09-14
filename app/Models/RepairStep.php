<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'repair_job_id',
    'user_id',
    'step_type',
    'title',
    'content',
    'cost',
    'performed_at',
])]
class RepairStep extends Model
{
    use HasFactory;

    protected $casts = [
        'cost' => 'decimal:2',
        'performed_at' => 'datetime',
    ];

    public function repairJob(): BelongsTo
    {
        return $this->belongsTo(RepairJob::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
