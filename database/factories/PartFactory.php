<?php

namespace Database\Factories;

use App\Models\Part;
use App\Models\PartCategory;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Part>
 */
class PartFactory extends Factory
{
    protected $model = Part::class;

    public function definition(): array
    {
        $name = fake()->unique()->randomElement([
            'Màn hình iPhone 14 Pro', 'Pin Samsung S24', 'Cáp sạc Type-C',
            'Loa iPhone 13', 'Camera trước Oppo', 'Bo mạch Dell XPS',
            'RAM DDR5 16GB', 'SSD 512GB', 'Bàn phím MacBook',
            'Màn hình iPad Air', 'Pin Vivo X100', 'Cáp Lightning',
        ]);

        return [
            'sku' => strtoupper(fake()->bothify('PRT-####')),
            'name' => $name,
            'slug' => Str::slug($name),
            'description' => fake()->optional()->sentence(),
            'category_id' => PartCategory::factory(),
            'brand' => fake()->optional()->randomElement(['Apple', 'Samsung', 'Xiaomi', 'Generic', 'OEM']),
            'unit' => fake()->randomElement(['cái', 'bộ', 'pcs', 'cái']),
            'cost_price' => fake()->randomFloat(2, 50000, 2000000),
            'sale_price' => fake()->randomFloat(2, 80000, 3000000),
            'stock_qty' => fake()->numberBetween(0, 100),
            'min_stock_qty' => fake()->numberBetween(2, 10),
            'location' => fake()->optional()->randomElement(['A-01', 'A-02', 'B-01', 'B-02', 'C-01', 'C-02']),
            'is_active' => true,
        ];
    }
}
