<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $customer->name }} - {{ config('app.name', 'RepairHub') }}</title>
    @if (file_exists(public_path('build/manifest.json')))
        @vite(['resources/css/app.css', 'resources/css/login.css', 'resources/css/customers.css'])
    @endif
</head>
<body class="customers-page">
    <div class="cust-layout">
        <header class="cust-header">
            <div class="cust-header-inner">
                <h1 class="cust-title">Chi tiết khách hàng</h1>
                <div class="flex items-center space-x-2">
                    <a href="{{ route('customers.edit', $customer) }}" class="cust-btn cust-btn-primary">Sửa</a>
                    <a href="{{ route('customers.index') }}" class="cust-btn cust-btn-secondary">Quay lại</a>
                </div>
            </div>
        </header>

        <main class="cust-main">
            <div class="cust-card">
                <div class="cust-card-header">
                    <h2 class="cust-title-main">{{ $customer->name }}</h2>
                </div>

                <div class="cust-card-body">
                    <dl class="cust-detail-grid">
                        <div class="cust-detail-item">
                            <dt>Số điện thoại</dt>
                            <dd>{{ $customer->phone }}</dd>
                        </div>

                        <div class="cust-detail-item">
                            <dt>Email</dt>
                            <dd>{{ $customer->email ?? '-' }}</dd>
                        </div>

                        <div class="cust-detail-item">
                            <dt>Địa chỉ</dt>
                            <dd>{{ $customer->address ?? '-' }}</dd>
                        </div>

                        <div class="cust-detail-item">
                            <dt>Ghi chú</dt>
                            <dd>{{ $customer->notes ?? '-' }}</dd>
                        </div>

                        <div class="cust-detail-item">
                            <dt>Ngày tạo</dt>
                            <dd>{{ $customer->created_at->format('d/m/Y H:i') }}</dd>
                        </div>

                        <div class="cust-detail-item">
                            <dt>Cập nhật lần cuối</dt>
                            <dd>{{ $customer->updated_at->format('d/m/Y H:i') }}</dd>
                        </div>
                    </dl>

                    <div class="cust-actions">
                        <a href="{{ route('customers.edit', $customer) }}" class="cust-btn cust-btn-primary">Sửa</a>
                        <form action="{{ route('customers.destroy', $customer) }}" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                    onclick="return confirm('Bạn có chắc chắn muốn xóa khách hàng này?')"
                                    class="cust-btn cust-btn-danger">Xóa</button>
                        </form>
                        <a href="{{ route('customers.index') }}" class="cust-btn cust-btn-secondary">Quay lại</a>
                    </div>
                </div>
            </div>
        </main>
    </div>
</body>
</html>