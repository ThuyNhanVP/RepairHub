<?php

namespace App\Notifications;

use App\Models\RepairJob;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class RepairStatusChangedNotification extends Notification
{
    use Queueable;

    public function __construct(
        public RepairJob $repairJob,
        public string $previousStatus,
    ) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * @return array<string, mixed>
     */
    public function toDatabase(object $notifiable): array
    {
        return [
            'title' => 'Cập nhật công việc sửa chữa',
            'message' => "Công việc #{$this->repairJob->id} đã chuyển từ {$this->previousStatus} sang {$this->repairJob->status}.",
            'repair_job_id' => $this->repairJob->id,
            'previous_status' => $this->previousStatus,
            'status' => $this->repairJob->status,
        ];
    }
}
