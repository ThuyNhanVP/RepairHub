<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Phiếu tiếp nhận - {{ config('app.name', 'RepairHub') }}</title>
    @if (file_exists(public_path('build/manifest.json')))
        @vite(['resources/css/app.css', 'resources/css/login.css', 'resources/css/customers.css'])
    @endif
</head>
<body class="customers-page">
    <div class="cust-layout">
        <header class="cust-header">
            <div class="cust-header-inner">
                <h1 class="cust-title">Phiếu tiếp nhận thiết bị</h1>
                <a href="{{ route('receptions.create') }}" class="cust-btn cust-btn-primary">Tiếp nhận mới</a>
            </div>
        </header>

        <main class="cust-main">
            @if (session('success'))
                <div class="cust-success">{{ session('success') }}</div>
            @endif

            <div class="cust-card">
                <div class="cust-card-header">
                    <h2 class="cust-title-main">Danh sách phiếu tiếp nhận</h2>
                </div>

                <div class="cust-card-body">
                    <div class="overflow-x-auto">
                        <table class="cust-table">
                            <thead>
                                <tr>
                                    <th>Mã phiếu</th>
                                    <th>Khách hàng</th>
                                    <th>Thiết bị</th>
                                    <th>Trạng thái</th>
                                    <th>Nhân viên</th>
                                    <th>Ngày nhận</th>
                                    <th class="text-right">Thao tác</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y" style="border-color: var(--color-cust-border);">
                                @forelse ($receptions as $reception)
                                    <tr class="cust-hover-row">
                                        <td class="font-medium">#{{ $reception->id }}</td>
                                        <td>{{ $reception->customer->name ?? '-' }}</td>
                                        <td>
                                            {{ $reception->device->brand ?? '' }} {{ $reception->device->model ?? '' }}
                                        </td>
                                        <td>
                                            <span class="cust-badge">{{ $reception->status }}</span>
                                        </td>
                                        <td>{{ $reception->user->name ?? '-' }}</td>
                                        <td class="text-slate-400">{{ $reception->received_at->format('d/m/Y H:i') }}</td>
                                        <td class="text-right">
                                            <a href="{{ route('receptions.show', $reception) }}" class="cust-link mr-4">Xem</a>
                                            <a href="{{ route('receptions.edit', $reception) }}" class="cust-link mr-4">Sửa</a>
                                            <form action="{{ route('receptions.destroy', $reception) }}" method="POST" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                        onclick="return confirm('Bạn có chắc chắn muốn xóa phiếu này?')"
                                                        class="text-red-400 hover:text-red-300">Xóa</button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="cust-empty">
                                            Chưa có phiếu tiếp nhận nào. <a href="{{ route('receptions.create') }}" class="cust-link">Tạo phiếu đầu tiên</a>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="cust-pagination mt-6">
                        {{ $receptions->links() }}
                    </div>
                </div>
            </div>
        </main>
    </div>
</body>
</html>