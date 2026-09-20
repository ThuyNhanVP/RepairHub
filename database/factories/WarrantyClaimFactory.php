<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Warranty;
use App\Models\WarrantyClaim;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

/**
 * @extends Factory<WarrantyClaim>
 */
class WarrantyClaimFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $receivedAt = Carbon::now()->subDays(fake()->numberBetween(0, 10));

        return [
            'warranty_id' => Warranty::factory(),
            'user_id' => User::factory(),
            'claim_code' => 'CL-'.Str::upper(Str::random(8)),
            'status' => 'received',
            'issue' => 'Thiết bị phát sinh lỗi trong thời gian bảo hành.',
            'resolution' => null,
            'received_at' => $receivedAt,
            'resolved_at' => null,
            'notes' => null,
        ];
    }
}
