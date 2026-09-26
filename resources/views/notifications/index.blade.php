<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Thông báo - {{ config('app.name', 'RepairHub') }}</title>
    @if (file_exists(public_path('build/manifest.json')))
        @vite(['resources/css/app.css', 'resources/css/login.css', 'resources/css/customers.css'])
    @endif
</head>
<body class="customers-page">
    <div class="cust-layout">
        <header class="cust-header">
            <div class="cust-header-inner">
                <div>
                    <h1 class="cust-title">Thông báo</h1>
                    <p class="text-sm" style="color: var(--color-cust-muted);">Theo dõi các cập nhật và thông tin từ hệ thống</p>
                </div>
                <div class="flex items-center gap-3">
                    <a href="{{ route('dashboard') }}" class="cust-btn cust-btn-secondary">Dashboard</a>
                </div>
            </div>
        </header>

        <main class="cust-main">
            @if (session('success'))
                <div class="cust-success">{{ session('success') }}</div>
            @endif

            <div class="mb-4 flex justify-end">
                <form method="POST" action="{{ route('notifications.mark-all-read') }}">
                    @csrf
                    <button type="submit" class="cust-btn cust-btn-primary">Đánh dấu tất cả đã đọc</button>
                </form>
            </div>

            <div class="cust-card">
                <div class="cust-card-header">
                    <h2 class="cust-title-main">Tất cả thông báo</h2>
                </div>

                <div class="cust-card-body">
                    <div class="overflow-x-auto">
                        <table class="cust-table">
                            <thead>
                                <tr>
                                    <th>Nội dung</th>
                                    <th>Thời gian</th>
                                    <th class="text-right">Thao tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($notifications as $notification)
                                    @php $data = $notification->data ?? []; @endphp
                                    <tr class="@if (is_null($notification->read_at)) bg-blue-500/5 @endif">
                                        <td>
                                            <div class="font-medium text-white">{{ $data['title'] ?? 'Thông báo hệ thống' }}</div>
                                            <div class="mt-1 text-sm text-slate-300">{{ $data['message'] ?? 'Bạn có thông báo mới.' }}</div>
                                            @if (! empty($data['previous_status']) && ! empty($data['status']))
                                                <div class="mt-2 text-xs text-slate-400">Từ {{ $data['previous_status'] }} → {{ $data['status'] }}</div>
                                            @endif
                                        </td>
                                        <td class="text-slate-400">{{ $notification->created_at->format('d/m/Y H:i') }}</td>
                                        <td class="text-right">
                                            @if (! empty($data['repair_job_id']))
                                                <a href="{{ route('repair-jobs.show', $data['repair_job_id']) }}" class="cust-link mr-3">Xem</a>
                                            @endif
                                            @if (is_null($notification->read_at))
                                                <form action="{{ route('notifications.read', $notification->id) }}" method="POST" class="inline">
                                                    @csrf
                                                    <button type="submit" class="text-blue-300 hover:text-blue-200">Đã đọc</button>
                                                </form>
                                            @else
                                                <span class="text-slate-400">Đã đọc</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="cust-empty">Bạn chưa có thông báo nào.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="cust-pagination mt-6">
                        {{ $notifications->links() }}
                    </div>
                </div>
            </div>
        </main>
    </div>
</body>
</html>
