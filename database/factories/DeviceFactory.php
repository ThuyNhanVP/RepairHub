<?php

namespace Database\Factories;

use App\Models\Device;
use App\Models\Customer;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Device>
 */
class DeviceFactory extends Factory
{
    protected $model = Device::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $brands = ['Apple', 'Samsung', 'Xiaomi', 'Oppo', 'Vivo', 'Realme', 'Huawei', 'Asus', 'Acer', 'Dell', 'HP', 'Lenovo', 'MSI'];
        $types = ['phone', 'laptop', 'tablet', 'watch', 'other'];

        return [
            'customer_id' => Customer::factory(),
            'brand' => fake()->randomElement($brands),
            'model' => fake()->word() . ' ' . fake()->randomNumber(2),
            'serial_number' => strtoupper(fake()->bothify('??##########')),
            'imei' => fake()->optional(0.7)->numerify('################'),
            'device_type' => fake()->randomElement($types),
            'color' => fake()->optional()->safeColorName(),
            'notes' => fake()->optional()->sentence(),
        ];
    }
}
