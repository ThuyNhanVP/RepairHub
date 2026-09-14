<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Công việc sửa chữa - {{ config('app.name', 'RepairHub') }}</title>
    @if (file_exists(public_path('build/manifest.json')))
        @vite(['resources/css/app.css', 'resources/css/login.css', 'resources/css/customers.css'])
    @endif
</head>
<body class="customers-page">
    <div class="cust-layout">
        <header class="cust-header">
            <div class="cust-header-inner">
                <h1 class="cust-title">Danh sách công việc sửa chữa</h1>
                <a href="{{ route('receptions.index') }}" class="cust-btn cust-btn-secondary">Phiếu tiếp nhận</a>
            </div>
        </header>

        <main class="cust-main">
            @if (session('success'))
                <div class="cust-success">{{ session('success') }}</div>
            @endif

            <div class="cust-card">
                <div class="cust-card-header">
                    <h2 class="cust-title-main">Tất cả công việc</h2>
                </div>

                <div class="cust-card-body">
                    <div class="overflow-x-auto">
                        <table class="cust-table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Khách hàng</th>
                                    <th>Thiết bị</th>
                                    <th>Kỹ thuật viên</th>
                                    <th>Trạng thái</th>
                                    <th>Chi phí dự kiến</th>
                                    <th class="text-right">Thao tác</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y" style="border-color: var(--color-cust-border);">
                                @forelse ($repairJobs as $job)
                                    <tr class="cust-hover-row">
                                        <td class="font-medium">#{{ $job->id }}</td>
                                        <td>{{ $job->reception->customer->name ?? '-' }}</td>
                                        <td>{{ $job->reception->device->brand ?? '' }} {{ $job->reception->device->model ?? '' }}</td>
                                        <td>{{ $job->technician->name ?? '-' }}</td>
                                        <td><span class="cust-badge">{{ $job->status }}</span></td>
                                        <td class="text-slate-400">{{ $job->estimated_cost ? number_format($job->estimated_cost, 0, ',', '.') . ' VNĐ' : '-' }}</td>
                                        <td class="text-right">
                                            <a href="{{ route('repair-jobs.show', $job) }}" class="cust-link mr-4">Xem</a>
                                            <a href="{{ route('repair-jobs.edit', $job) }}" class="cust-link mr-4">Sửa</a>
                                            <form action="{{ route('repair-jobs.destroy', $job) }}" method="POST" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" onclick="return confirm('Bạn có chắc chắn muốn xóa?')" class="text-red-400 hover:text-red-300">Xóa</button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="cust-empty">Chưa có công việc sửa chữa nào.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="cust-pagination mt-6">
                        {{ $repairJobs->links() }}
                    </div>
                </div>
            </div>
        </main>
    </div>
</body>
</html>