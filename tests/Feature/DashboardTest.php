<?php

namespace Tests\Feature;

use App\Models\Part;
use App\Models\Reception;
use App\Models\RepairJob;
use App\Models\User;
use App\Models\Warranty;
use App\Models\WarrantyClaim;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DashboardTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_user_can_view_dashboard_metrics(): void
    {
        $user = User::factory()->create();
        Reception::factory()->create();
        RepairJob::factory()->create(['status' => 'repairing']);
        RepairJob::factory()->create(['status' => 'completed', 'final_cost' => 1500000, 'updated_at' => now()]);
        Warranty::factory()->create(['status' => 'active', 'end_date' => today()->addMonth()]);
        WarrantyClaim::factory()->create(['status' => 'diagnosing']);
        Part::factory()->create(['stock_qty' => 1, 'min_stock_qty' => 5]);

        $response = $this->actingAs($user)->get(route('dashboard'));

        $response->assertOk();
        $response->assertSee('Dashboard RepairHub');
        $response->assertSee('Tổng tiếp nhận');
        $response->assertSee('VNĐ');
        $response->assertSee('Linh kiện sắp hết');
    }

    public function test_dashboard_requires_authentication(): void
    {
        $response = $this->get(route('dashboard'));

        $response->assertRedirect(route('login'));
    }
}
