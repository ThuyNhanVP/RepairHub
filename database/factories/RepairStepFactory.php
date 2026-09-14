<?php

namespace Database\Factories;

use App\Models\RepairStep;
use App\Models\RepairJob;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<RepairStep>
 */
class RepairStepFactory extends Factory
{
    protected $model = RepairStep::class;

    public function definition(): array
    {
        $types = ['diagnosis', 'quote', 'approval', 'repair_note', 'part_used', 'completion'];

        return [
            'repair_job_id' => RepairJob::factory(),
            'user_id' => User::factory(),
            'step_type' => fake()->randomElement($types),
            'title' => fake()->sentence(3),
            'content' => fake()->sentence(),
            'cost' => fake()->optional(0.5)->randomFloat(2, 10000, 1000000),
            'performed_at' => fake()->dateTimeBetween('-7 days', 'now'),
        ];
    }
}
