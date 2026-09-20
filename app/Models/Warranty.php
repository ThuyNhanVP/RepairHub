<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable([
    'repair_job_id',
    'customer_id',
    'device_id',
    'warranty_code',
    'start_date',
    'end_date',
    'duration_months',
    'status',
    'terms',
])]
class Warranty extends Model
{
    use HasFactory;

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'duration_months' => 'integer',
    ];

    public function repairJob(): BelongsTo
    {
        return $this->belongsTo(RepairJob::class);
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function device(): BelongsTo
    {
        return $this->belongsTo(Device::class);
    }

    public function claims(): HasMany
    {
        return $this->hasMany(WarrantyClaim::class)->latest('received_at');
    }

    public function isExpired(): bool
    {
        return $this->status === 'expired' || $this->end_date->isPast();
    }
}
