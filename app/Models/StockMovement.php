<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'part_id',
    'type',
    'qty',
    'unit_cost',
    'reference_type',
    'reference_id',
    'reference_number',
    'notes',
    'user_id',
    'performed_at',
])]
class StockMovement extends Model
{
    use HasFactory;

    protected $casts = [
        'qty' => 'integer',
        'unit_cost' => 'decimal:2',
        'performed_at' => 'datetime',
    ];

    public function part(): BelongsTo
    {
        return $this->belongsTo(Part::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getTypeLabelAttribute(): string
    {
        return match ($this->type) {
            'in' => 'Nhập kho',
            'out' => 'Xuất kho',
            'adjustment' => 'Điều chỉnh',
            default => $this->type,
        };
    }
}
