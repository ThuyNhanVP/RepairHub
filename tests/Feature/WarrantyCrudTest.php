<?php

namespace Tests\Feature;

use App\Models\RepairJob;
use App\Models\User;
use App\Models\Warranty;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class WarrantyCrudTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
    }

    public function test_can_create_warranty_from_completed_repair_job(): void
    {
        $repairJob = RepairJob::factory()->create(['status' => 'completed']);

        $response = $this->actingAs($this->user)->post(route('warranties.store'), [
            'repair_job_id' => $repairJob->id,
            'start_date' => '2026-09-19',
            'duration_months' => 6,
            'status' => 'active',
            'terms' => 'Bảo hành linh kiện thay thế.',
        ]);

        $warranty = $repairJob->fresh()->warranty;

        $response->assertRedirect(route('warranties.show', $warranty));
        $this->assertNotNull($warranty);
        $this->assertSame('2027-03-19', $warranty->end_date->toDateString());
        $this->assertDatabaseHas('warranties', [
            'repair_job_id' => $repairJob->id,
            'status' => 'active',
        ]);
    }

    public function test_cannot_create_warranty_for_incomplete_repair_job(): void
    {
        $repairJob = RepairJob::factory()->create(['status' => 'repairing']);

        $response = $this->actingAs($this->user)->post(route('warranties.store'), [
            'repair_job_id' => $repairJob->id,
            'start_date' => '2026-09-19',
            'duration_months' => 6,
            'status' => 'active',
        ]);

        $response->assertStatus(422);
        $this->assertDatabaseCount('warranties', 0);
    }

    public function test_can_list_and_search_warranties(): void
    {
        $warranty = Warranty::factory()->create([
            'warranty_code' => 'WH-SEARCH-001',
        ]);

        $response = $this->actingAs($this->user)->get(route('warranties.index', [
            'search' => 'WH-SEARCH-001',
        ]));

        $response->assertOk();
        $response->assertSee('WH-SEARCH-001');
    }

    public function test_can_update_warranty(): void
    {
        $warranty = Warranty::factory()->create();

        $response = $this->actingAs($this->user)->put(route('warranties.update', $warranty), [
            'warranty_code' => $warranty->warranty_code,
            'start_date' => '2026-09-20',
            'duration_months' => 12,
            'status' => 'void',
            'terms' => 'Không áp dụng bảo hành.',
        ]);

        $response->assertRedirect(route('warranties.show', $warranty));
        $warranty->refresh();
        $this->assertSame('void', $warranty->status);
        $this->assertSame('2027-09-20', $warranty->end_date->toDateString());
    }

    public function test_requires_authentication(): void
    {
        $response = $this->get(route('warranties.index'));

        $response->assertRedirect(route('login'));
    }
}
