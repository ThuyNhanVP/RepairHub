<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Yêu cầu bảo hành - {{ config('app.name', 'RepairHub') }}</title>
    @if (file_exists(public_path('build/manifest.json')))
        @vite(['resources/css/app.css', 'resources/css/login.css', 'resources/css/customers.css'])
    @endif
</head>
<body class="customers-page">
    <div class="cust-layout">
        <header class="cust-header"><div class="cust-header-inner">
            <h1 class="cust-title">Yêu cầu bảo hành</h1>
            <a href="{{ route('warranty-claims.create') }}" class="cust-btn cust-btn-primary">Tiếp nhận bảo hành</a>
        </div></header>
        <main class="cust-main">
            @if (session('success'))<div class="cust-success">{{ session('success') }}</div>@endif
            <form method="GET" action="{{ route('warranty-claims.index') }}" class="cust-card mb-6">
                <div class="cust-card-body flex flex-wrap gap-4">
                    <input name="search" value="{{ request('search') }}" placeholder="Mã yêu cầu, mã bảo hành, khách hàng..." class="cust-input min-w-[240px] flex-1">
                    <select name="status" class="cust-input w-full md:max-w-[200px]">
                        <option value="all">Tất cả trạng thái</option>
                        @foreach (['received' => 'Đã tiếp nhận', 'diagnosing' => 'Đang kiểm tra', 'approved' => 'Đã duyệt', 'rejected' => 'Từ chối', 'completed' => 'Hoàn tất', 'cancelled' => 'Đã hủy'] as $value => $label)
                            <option value="{{ $value }}" @selected(request('status') === $value)>{{ $label }}</option>
                        @endforeach
                    </select>
                    <button class="cust-btn cust-btn-secondary">Lọc</button>
                </div>
            </form>
            <div class="cust-card"><div class="cust-card-header"><h2 class="cust-title-main">Danh sách yêu cầu</h2></div><div class="cust-card-body overflow-x-auto">
                <table class="cust-table"><thead><tr><th>Mã yêu cầu</th><th>Khách hàng</th><th>Mã bảo hành</th><th>Ngày tiếp nhận</th><th>Trạng thái</th><th></th></tr></thead>
                <tbody class="divide-y">@forelse ($claims as $claim)
                    <tr><td class="font-medium">{{ $claim->claim_code }}</td><td>{{ $claim->warranty->customer->name }}<br><span class="text-xs">{{ $claim->warranty->customer->phone }}</span></td><td>{{ $claim->warranty->warranty_code }}</td><td>{{ $claim->received_at->format('d/m/Y H:i') }}</td><td><span class="cust-badge">{{ $claim->status }}</span></td><td class="text-right"><a class="cust-link" href="{{ route('warranty-claims.show', $claim) }}">Xem</a></td></tr>
                @empty<tr><td colspan="6" class="cust-empty">Chưa có yêu cầu bảo hành.</td></tr>@endforelse</tbody></table>
                <div class="cust-pagination mt-6">{{ $claims->links() }}</div>
            </div></div>
        </main>
    </div>
</body>
</html>
