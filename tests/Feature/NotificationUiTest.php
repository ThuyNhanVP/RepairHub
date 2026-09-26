<?php

namespace Tests\Feature;

use App\Models\RepairJob;
use App\Models\User;
use App\Notifications\RepairStatusChangedNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class NotificationUiTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_user_can_view_notification_list(): void
    {
        $user = User::factory()->create();
        $repairJob = RepairJob::factory()->create();
        $user->notify(new RepairStatusChangedNotification($repairJob, 'pending'));

        $response = $this->actingAs($user)->get(route('notifications.index'));

        $response->assertOk();
        $response->assertSee('Thông báo');
        $response->assertSee('Cập nhật công việc sửa chữa');
    }

    public function test_user_can_mark_a_notification_as_read(): void
    {
        $user = User::factory()->create();
        $repairJob = RepairJob::factory()->create();
        $notification = $user->notify(new RepairStatusChangedNotification($repairJob, 'pending'));

        $response = $this->actingAs($user)
            ->post(route('notifications.read', $user->notifications()->first()->id));

        $response->assertRedirect();
        $this->assertNotNull($user->fresh()->notifications()->first()->read_at);
    }

    public function test_user_can_mark_all_notifications_as_read(): void
    {
        $user = User::factory()->create();
        $repairJob = RepairJob::factory()->create();

        $user->notify(new RepairStatusChangedNotification($repairJob, 'pending'));
        $user->notify(new RepairStatusChangedNotification($repairJob, 'diagnosed'));

        $this->actingAs($user)
            ->post(route('notifications.mark-all-read'))
            ->assertRedirect();

        $this->assertSame(0, $user->fresh()->unreadNotifications()->count());
    }

    public function test_notification_page_requires_authentication(): void
    {
        $response = $this->get(route('notifications.index'));

        $response->assertRedirect(route('login'));
    }
}
