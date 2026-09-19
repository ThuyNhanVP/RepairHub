<?php

namespace Database\Factories;

use App\Models\Customer;
use App\Models\Device;
use App\Models\RepairJob;
use App\Models\Warranty;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

/**
 * @extends Factory<Warranty>
 */
class WarrantyFactory extends Factory
{
    public function configure(): static
    {
        return $this->afterCreating(function (Warranty $warranty): void {
            $warranty->update([
                'customer_id' => $warranty->repairJob->reception->customer_id,
                'device_id' => $warranty->repairJob->reception->device_id,
            ]);
        });
    }

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $startDate = Carbon::today()->subDays(fake()->numberBetween(0, 30));
        $durationMonths = fake()->numberBetween(3, 12);

        return [
            'repair_job_id' => RepairJob::factory(),
            'customer_id' => Customer::factory(),
            'device_id' => Device::factory(),
            'warranty_code' => 'WH-'.Str::upper(Str::random(8)),
            'start_date' => $startDate,
            'end_date' => $startDate->copy()->addMonthsNoOverflow($durationMonths),
            'duration_months' => $durationMonths,
            'status' => 'active',
            'terms' => 'Bảo hành lỗi kỹ thuật trong thời hạn bảo hành.',
        ];
    }
}
