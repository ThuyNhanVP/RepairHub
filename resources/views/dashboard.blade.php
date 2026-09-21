<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dashboard - {{ config('app.name', 'RepairHub') }}</title>
    @if (file_exists(public_path('build/manifest.json')))
        @vite(['resources/css/app.css', 'resources/css/login.css', 'resources/css/customers.css'])
    @endif
</head>
<body class="customers-page">
    <div class="cust-layout">
        <header class="cust-header">
            <div class="cust-header-inner">
                <div>
                    <h1 class="cust-title">Dashboard RepairHub</h1>
                    <p class="text-sm" style="color: var(--color-cust-muted);">Tổng quan hoạt động sửa chữa và bảo hành</p>
                </div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="cust-btn cust-btn-secondary">Đăng xuất</button>
                </form>
            </div>
        </header>
        <main class="cust-main">
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-4">
                @foreach ([
                    ['label' => 'Tổng tiếp nhận', 'value' => $summary['totalReceptions'], 'link' => route('receptions.index')],
                    ['label' => 'Đang sửa chữa', 'value' => $summary['activeRepairJobs'], 'link' => route('repair-jobs.index')],
                    ['label' => 'Bảo hành còn hạn', 'value' => $summary['activeWarranties'], 'link' => route('warranties.index')],
                    ['label' => 'Yêu cầu đang xử lý', 'value' => $summary['openWarrantyClaims'], 'link' => route('warranty-claims.index')],
                ] as $metric)
                    <a href="{{ $metric['link'] }}" class="cust-card p-5 transition hover:border-blue-500">
                        <p class="text-sm" style="color: var(--color-cust-muted);">{{ $metric['label'] }}</p>
                        <p class="mt-2 text-3xl font-semibold">{{ number_format($metric['value']) }}</p>
                    </a>
                @endforeach
            </div>
            <div class="mt-6 grid grid-cols-1 gap-6 lg:grid-cols-3">
                <div class="cust-card lg:col-span-2">
                    <div class="cust-card-header flex items-center justify-between">
                        <div><h2 class="cust-title-main">Doanh thu tháng này</h2><p class="text-sm" style="color: var(--color-cust-muted);">{{ $monthStart->format('d/m/Y') }} - {{ $monthEnd->format('d/m/Y') }}</p></div>
                        <span class="text-xl font-semibold">{{ number_format($summary['monthlyRevenue'], 0, ',', '.') }} VNĐ</span>
                    </div>
                    <div class="cust-card-body"><div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                        <div class="rounded-lg p-4" style="background: var(--color-cust-bg);"><p class="text-sm" style="color: var(--color-cust-muted);">Công việc hoàn tất trong tháng</p><p class="mt-2 text-2xl font-semibold">{{ number_format($summary['completedThisMonth']) }}</p></div>
                        <a href="{{ route('parts.index', ['low_stock' => 1]) }}" class="rounded-lg p-4 transition hover:border-blue-500" style="background: var(--color-cust-bg); border: 1px solid var(--color-cust-border);"><p class="text-sm" style="color: var(--color-cust-muted);">Linh kiện sắp hết</p><p class="mt-2 text-2xl font-semibold">{{ number_format($summary['lowStockParts']) }}</p></a>
                    </div></div>
                </div>
                <div class="cust-card"><div class="cust-card-header"><h2 class="cust-title-main">Trạng thái sửa chữa</h2></div><div class="cust-card-body space-y-3">
                    @foreach ($repairStatusLabels as $status => $label)
                        <div class="flex items-center justify-between text-sm"><span>{{ $label }}</span><span class="cust-badge">{{ $repairStatusCounts[$status] ?? 0 }}</span></div>
                    @endforeach
                </div></div>
            </div>
            <div class="mt-6 grid grid-cols-1 gap-6 xl:grid-cols-2">
                <div class="cust-card"><div class="cust-card-header flex items-center justify-between"><h2 class="cust-title-main">Tiếp nhận gần đây</h2><a href="{{ route('receptions.index') }}" class="cust-link">Xem tất cả</a></div><div class="cust-card-body overflow-x-auto">
                    <table class="cust-table"><thead><tr><th>Khách hàng</th><th>Thiết bị</th><th>Ngày nhận</th></tr></thead><tbody>
                        @forelse ($recentReceptions as $reception)
                            <tr><td>{{ $reception->customer->name }}</td><td>{{ $reception->device->brand }} {{ $reception->device->model }}</td><td>{{ $reception->received_at->format('d/m/Y') }}</td></tr>
                        @empty
                            <tr><td colspan="3" class="cust-empty">Chưa có phiếu tiếp nhận.</td></tr>
                        @endforelse
                    </tbody></table>
                </div></div>
                <div class="cust-card"><div class="cust-card-header flex items-center justify-between"><h2 class="cust-title-main">Linh kiện sắp hết</h2><a href="{{ route('parts.index', ['low_stock' => 1]) }}" class="cust-link">Xem tất cả</a></div><div class="cust-card-body overflow-x-auto">
                    <table class="cust-table"><thead><tr><th>Linh kiện</th><th>Tồn kho</th><th>Mức tối thiểu</th></tr></thead><tbody>
                        @forelse ($lowStockPartList as $part)
                            <tr><td>{{ $part->name }}<br><span class="text-xs">{{ $part->sku }}</span></td><td>{{ $part->stock_qty }} {{ $part->unit }}</td><td>{{ $part->min_stock_qty }}</td></tr>
                        @empty
                            <tr><td colspan="3" class="cust-empty">Không có linh kiện sắp hết.</td></tr>
                        @endforelse
                    </tbody></table>
                </div></div>
            </div>
        </main>
    </div>
</body>
</html>
