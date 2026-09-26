<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class NotificationController extends Controller
{
    public function index(): View
    {
        $notifications = auth()->user()->notifications()->latest()->paginate(15);

        return view('notifications.index', compact('notifications'));
    }

    public function markAsRead(string $notificationId): RedirectResponse
    {
        $notification = auth()->user()->notifications()->whereKey($notificationId)->firstOrFail();
        $notification->markAsRead();

        return back()->with('success', 'Đã đánh dấu thông báo là đã đọc.');
    }

    public function markAllAsRead(): RedirectResponse
    {
        auth()->user()->unreadNotifications()->update(['read_at' => now()]);

        return back()->with('success', 'Đã đánh dấu tất cả thông báo là đã đọc.');
    }
}
