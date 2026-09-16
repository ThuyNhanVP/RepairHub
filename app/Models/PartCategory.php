<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable([
    'name',
    'slug',
    'description',
    'parent_id',
    'sort_order',
    'is_active',
])]
class PartCategory extends Model
{
    use HasFactory;

    public function parent(): BelongsTo
    {
        return $this->belongsTo(PartCategory::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(PartCategory::class, 'parent_id')->orderBy('sort_order');
    }

    public function parts(): HasMany
    {
        return $this->hasMany(Part::class);
    }
}
