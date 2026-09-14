<?php

namespace Database\Factories;

use App\Models\RepairJob;
use App\Models\Reception;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<RepairJob>
 */
class RepairJobFactory extends Factory
{
    protected $model = RepairJob::class;

    public function definition(): array
    {
        $statuses = ['pending', 'diagnosed', 'quoted', 'approved', 'repairing', 'waiting_parts', 'completed', 'cancelled'];

        return [
            'reception_id' => Reception::factory(),
            'technician_id' => User::factory(),
            'status' => fake()->randomElement($statuses),
            'diagnosis' => fake()->optional(0.6)->sentence(),
            'estimated_cost' => fake()->optional(0.6)->randomFloat(2, 100000, 5000000),
            'final_cost' => fake()->optional(0.4)->randomFloat(2, 100000, 5000000),
            'notes' => fake()->optional()->sentence(),
        ];
    }
}
