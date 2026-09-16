<?php

namespace Database\Factories;

use App\Models\StockMovement;
use App\Models\Part;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<StockMovement>
 */
class StockMovementFactory extends Factory
{
    protected $model = StockMovement::class;

    public function definition(): array
    {
        $types = ['in', 'out', 'adjustment'];

        return [
            'part_id' => Part::factory(),
            'type' => fake()->randomElement($types),
            'qty' => fake()->numberBetween(1, 50),
            'unit_cost' => fake()->randomFloat(2, 50000, 2000000),
            'reference_type' => null,
            'reference_id' => null,
            'reference_number' => fake()->optional()->bothify('MO-####'),
            'notes' => fake()->optional()->sentence(),
            'user_id' => User::factory(),
            'performed_at' => fake()->dateTimeBetween('-30 days', 'now'),
        ];
    }
}
