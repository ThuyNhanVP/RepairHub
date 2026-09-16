<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable([
    'sku',
    'name',
    'slug',
    'description',
    'category_id',
    'brand',
    'unit',
    'cost_price',
    'sale_price',
    'stock_qty',
    'min_stock_qty',
    'location',
    'is_active',
])]
class Part extends Model
{
    use HasFactory;

    protected $casts = [
        'cost_price' => 'decimal:2',
        'sale_price' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(PartCategory::class);
    }

    public function stockMovements(): HasMany
    {
        return $this->hasMany(StockMovement::class)->orderBy('performed_at', 'desc');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeLowStock($query)
    {
        return $query->where('stock_qty', '<=', 'min_stock_qty');
    }

    public function isLowStock(): bool
    {
        return $this->stock_qty <= $this->min_stock_qty;
    }

    public function recordMovement(string $type, int $qty, ?float $unitCost = null, ?string $referenceType = null, ?int $referenceId = null, ?string $referenceNumber = null, ?string $notes = null, ?int $userId = null): StockMovement
    {
        $oldQty = $this->stock_qty;
        
        if ($type === 'in') {
            $this->increment('stock_qty', $qty);
        } elseif ($type === 'out') {
            $this->decrement('stock_qty', $qty);
        } elseif ($type === 'adjustment') {
            $this->stock_qty = $qty;
            $this->save();
        }

        return StockMovement::create([
            'part_id' => $this->id,
            'type' => $type,
            'qty' => $qty,
            'unit_cost' => $unitCost,
            'reference_type' => $referenceType,
            'reference_id' => $referenceId,
            'reference_number' => $referenceNumber,
            'notes' => $notes,
            'user_id' => $userId ?? auth()->id(),
        ]);
    }
}
