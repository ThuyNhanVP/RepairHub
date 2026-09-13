<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['customer_id', 'brand', 'model', 'serial_number', 'imei', 'device_type', 'color', 'notes'])]
class Device extends Model
{
    use HasFactory;

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function receptions(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Reception::class);
    }
}
