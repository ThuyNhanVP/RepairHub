<?php

namespace Tests\Feature;

use App\Models\RepairJob;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RepairJobCrudTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
    }

    public function test_can_list_repair_jobs(): void
    {
        RepairJob::factory()->count(3)->create();

        $response = $this->actingAs($this->user)->get(route('repair-jobs.index'));

        $response->assertStatus(200);
        $response->assertSee('Danh sách công việc sửa chữa');
    }

    public function test_can_show_repair_job(): void
    {
        $repairJob = RepairJob::factory()->create();

        $response = $this->actingAs($this->user)->get(route('repair-jobs.show', $repairJob));

        $response->assertStatus(200);
        $response->assertSee($repairJob->id);
    }

    public function test_can_update_repair_job(): void
    {
        $repairJob = RepairJob::factory()->create(['status' => 'pending']);

        $response = $this->actingAs($this->user)->put(route('repair-jobs.update', $repairJob), [
            'status' => 'diagnosed',
            'diagnosis' => 'Lỗi mainboard',
            'estimated_cost' => 1500000,
        ]);

        $response->assertRedirect(route('repair-jobs.show', $repairJob));
        $this->assertDatabaseHas('repair_jobs', [
            'id' => $repairJob->id,
            'status' => 'diagnosed',
            'diagnosis' => 'Lỗi mainboard',
        ]);

        // Check if status change step is created
        $this->assertDatabaseHas('repair_steps', [
            'repair_job_id' => $repairJob->id,
            'step_type' => 'status_change',
        ]);
        $this->assertDatabaseHas('notifications', [
            'notifiable_id' => $repairJob->technician_id,
            'type' => 'App\\Notifications\\RepairStatusChangedNotification',
        ]);
    }

    public function test_does_not_notify_when_repair_status_does_not_change(): void
    {
        $repairJob = RepairJob::factory()->create(['status' => 'pending']);

        $response = $this->actingAs($this->user)->put(route('repair-jobs.update', $repairJob), [
            'status' => 'pending',
        ]);

        $response->assertRedirect(route('repair-jobs.show', $repairJob));
        $this->assertDatabaseCount('notifications', 0);
    }

    public function test_can_add_repair_step(): void
    {
        $repairJob = RepairJob::factory()->create();

        $response = $this->actingAs($this->user)->post(route('repair-jobs.add-step', $repairJob), [
            'step_type' => 'part_used',
            'title' => 'Thay pin mới',
            'content' => 'Pin zin chính hãng',
            'cost' => 500000,
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('repair_steps', [
            'repair_job_id' => $repairJob->id,
            'step_type' => 'part_used',
            'title' => 'Thay pin mới',
        ]);
    }

    public function test_requires_authentication(): void
    {
        $response = $this->get(route('repair-jobs.index'));
        $response->assertRedirect(route('login'));
    }
}
