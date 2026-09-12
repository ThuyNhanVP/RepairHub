<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Khách hàng - {{ config('app.name', 'RepairHub') }}</title>
    @if (file_exists(public_path('build/manifest.json')))
        @vite(['resources/css/app.css', 'resources/css/login.css', 'resources/css/customers.css'])
    @endif
</head>
<body class="customers-page">
    <div class="cust-layout">
        <header class="cust-header">
            <div class="cust-header-inner">
                <h1 class="cust-title">Quản lý khách hàng</h1>
                <a href="{{ route('customers.create') }}" class="cust-btn cust-btn-primary">Thêm khách hàng</a>
            </div>
        </header>

        <main class="cust-main">
            @if (session('success'))
                <div class="cust-success">{{ session('success') }}</div>
            @endif

            <div class="cust-card">
                <div class="cust-card-header">
                    <h2 class="cust-title-main">Danh sách khách hàng</h2>
                </div>

                <div class="cust-card-body">
                    <div class="overflow-x-auto">
                        <table class="cust-table">
                            <thead>
                                <tr>
                                    <th>Tên</th>
                                    <th>Số điện thoại</th>
                                    <th>Email</th>
                                    <th>Địa chỉ</th>
                                    <th class="text-right">Thao tác</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y" style="border-color: var(--color-cust-border);">
                                @forelse ($customers as $customer)
                                    <tr class="cust-hover-row">
                                        <td class="font-medium">{{ $customer->name }}</td>
                                        <td class="text-slate-400">{{ $customer->phone }}</td>
                                        <td class="text-slate-400">{{ $customer->email ?? '-' }}</td>
                                        <td class="max-w-xs truncate text-slate-400">{{ $customer->address ?? '-' }}</td>
                                        <td class="text-right">
                                            <a href="{{ route('customers.show', $customer) }}" class="cust-link mr-4">Xem</a>
                                            <a href="{{ route('customers.edit', $customer) }}" class="cust-link mr-4">Sửa</a>
                                            <form action="{{ route('customers.destroy', $customer) }}" method="POST" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                        onclick="return confirm('Bạn có chắc chắn muốn xóa khách hàng này?')"
                                                        class="text-red-400 hover:text-red-300">Xóa</button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="cust-empty">
                                            Chưa có khách hàng nào. <a href="{{ route('customers.create') }}" class="cust-link">Tạo khách hàng đầu tiên</a>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="cust-pagination mt-6">
                        {{ $customers->links() }}
                    </div>
                </div>
            </div>
        </main>
    </div>
</body>
</html>