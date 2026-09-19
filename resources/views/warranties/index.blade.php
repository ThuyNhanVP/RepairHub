<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Bảo hành - {{ config('app.name', 'RepairHub') }}</title>
    @if (file_exists(public_path('build/manifest.json')))
        @vite(['resources/css/app.css', 'resources/css/login.css', 'resources/css/customers.css'])
    @endif
</head>
<body class="customers-page">
    <div class="cust-layout">
        <header class="cust-header">
            <div class="cust-header-inner">
                <h1 class="cust-title">Quản lý bảo hành</h1>
                <a href="{{ route('warranties.create') }}" class="cust-btn cust-btn-primary">Tạo bảo hành</a>
            </div>
        </header>
        <main class="cust-main">
            @if (session('success'))
                <div class="cust-success">{{ session('success') }}</div>
            @endif
            <form method="GET" action="{{ route('warranties.index') }}" class="cust-card mb-6">
                <div class="cust-card-body flex flex-wrap gap-4">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Mã bảo hành, khách hàng, SĐT, serial..." class="cust-input min-w-[240px] flex-1">
                    <select name="status" class="cust-input w-full md:max-w-[180px]">
                        <option value="all">Tất cả trạng thái</option>
                        <option value="active" @selected(request('status') === 'active')>Còn hạn</option>
                        <option value="expired" @selected(request('status') === 'expired')>Hết hạn</option>
                        <option value="void" @selected(request('status') === 'void')>Đã hủy</option>
                    </select>
                    <button type="submit" class="cust-btn cust-btn-secondary">Lọc</button>
                </div>
            </form>
            <div class="cust-card">
                <div class="cust-card-header"><h2 class="cust-title-main">Danh sách phiếu bảo hành</h2></div>
                <div class="cust-card-body overflow-x-auto">
                    <table class="cust-table">
                        <thead><tr><th>Mã</th><th>Khách hàng</th><th>Thiết bị</th><th>Thời hạn</th><th>Trạng thái</th><th class="text-right">Thao tác</th></tr></thead>
                        <tbody class="divide-y">
                            @forelse ($warranties as $warranty)
                                <tr>
                                    <td class="font-medium">{{ $warranty->warranty_code }}</td>
                                    <td>{{ $warranty->customer->name }}<br><span class="text-xs">{{ $warranty->customer->phone }}</span></td>
                                    <td>{{ $warranty->device->brand }} {{ $warranty->device->model }}</td>
                                    <td>{{ $warranty->start_date->format('d/m/Y') }} - {{ $warranty->end_date->format('d/m/Y') }}</td>
                                    <td><span class="cust-badge">{{ $warranty->status === 'void' ? 'Đã hủy' : ($warranty->isExpired() ? 'Hết hạn' : 'Còn hạn') }}</span></td>
                                    <td class="text-right"><a href="{{ route('warranties.show', $warranty) }}" class="cust-link mr-4">Xem</a><a href="{{ route('warranties.edit', $warranty) }}" class="cust-link">Sửa</a></td>
                                </tr>
                            @empty
                                <tr><td colspan="6" class="cust-empty">Chưa có phiếu bảo hành.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                    <div class="cust-pagination mt-6">{{ $warranties->links() }}</div>
                </div>
            </div>
        </main>
    </div>
</body>
</html>
