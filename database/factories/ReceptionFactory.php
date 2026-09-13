<?php

namespace Database\Factories;

use App\Models\Reception;
use App\Models\Customer;
use App\Models\Device;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Reception>
 */
class ReceptionFactory extends Factory
{
    protected $model = Reception::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $statuses = ['received', 'diagnosing', 'quoted', 'waiting_approval', 'repairing', 'waiting_parts', 'completed', 'delivered', 'cancelled'];

        return [
            'customer_id' => Customer::factory(),
            'device_id' => Device::factory(),
            'user_id' => User::factory(),
            'status' => fake()->randomElement($statuses),
            'description' => fake()->sentence(),
            'diagnosis' => fake()->optional(0.5)->sentence(),
            'estimated_cost' => fake()->optional(0.6)->randomFloat(2, 100, 5000),
            'final_cost' => fake()->optional(0.4)->randomFloat(2, 100, 5000),
            'received_at' => fake()->dateTimeBetween('-30 days', 'now'),
            'estimated_completion_at' => fake()->optional(0.7)->dateTimeBetween('now', '+14 days'),
            'completed_at' => fake()->optional(0.3)->dateTimeBetween('-7 days', 'now'),
            'delivered_at' => fake()->optional(0.2)->dateTimeBetween('-3 days', 'now'),
            'notes' => fake()->optional()->sentence(),
        ];
    }
}
