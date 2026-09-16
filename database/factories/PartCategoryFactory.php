<?php

namespace Database\Factories;

use App\Models\PartCategory;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<PartCategory>
 */
class PartCategoryFactory extends Factory
{
    protected $model = PartCategory::class;

    public function definition(): array
    {
        $name = fake()->unique()->randomElement([
            'Màn hình', 'Pin', 'Bo mạch', 'Cáp sạc', 'Loa', 'Micro',
            'Camera', 'Bộ nhớ', 'CPU', 'RAM', 'SSD', 'Bàn phím',
        ]);

        return [
            'name' => $name,
            'slug' => Str::slug($name),
            'description' => fake()->optional()->sentence(),
            'parent_id' => null,
            'sort_order' => fake()->numberBetween(0, 100),
            'is_active' => true,
        ];
    }
}
