<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $warranty->warranty_code }} - Bảo hành</title>@if (file_exists(public_path('build/manifest.json')))
    @vite(['resources/css/app.css', 'resources/css/login.css', 'resources/css/customers.css']) @endif
</head>

<body class="customers-page">
    <div class="cust-layout">
        <header class="cust-header">
            <div class="cust-header-inner">
                <h1 class="cust-title">Phiếu bảo hành {{ $warranty->warranty_code }}</h1>
                <div class="flex gap-2"><a href="{{ route('warranties.edit', $warranty) }}"
                        class="cust-btn cust-btn-primary">Sửa</a><a href="{{ route('warranties.index') }}"
                        class="cust-btn cust-btn-secondary">Quay lại</a></div>
            </div>
        </header>
        <main class="cust-main">@if (session('success'))
        <div class="cust-success">{{ session('success') }}</div>@endif<div class="cust-card">
                <div class="cust-card-header">
                    <h2 class="cust-title-main">Thông tin bảo hành</h2>
                </div>
                <div class="cust-card-body">
                    <dl class="cust-detail-grid">
                        <div class="cust-detail-item">
                            <dt>Khách hàng</dt>
                            <dd>{{ $warranty->customer->name }} - {{ $warranty->customer->phone }}</dd>
                        </div>
                        <div class="cust-detail-item">
                            <dt>Thiết bị</dt>
                            <dd>{{ $warranty->device->brand }} {{ $warranty->device->model }}
                                ({{ $warranty->device->serial_number ?: $warranty->device->imei ?: '-' }})</dd>
                        </div>
                        <div class="cust-detail-item">
                            <dt>Công việc sửa chữa</dt>
                            <dd><a class="cust-link"
                                    href="{{ route('repair-jobs.show', $warranty->repairJob) }}">#{{ $warranty->repair_job_id }}</a>
                            </dd>
                        </div>
                        <div class="cust-detail-item">
                            <dt>Trạng thái</dt>
                            <dd><span
                                    class="cust-badge">{{ $warranty->status === 'void' ? 'Đã hủy' : ($warranty->isExpired() ? 'Hết hạn' : 'Còn hạn') }}</span>
                            </dd>
                        </div>
                        <div class="cust-detail-item">
                            <dt>Thời hạn</dt>
                            <dd>{{ $warranty->start_date->format('d/m/Y') }} -
                                {{ $warranty->end_date->format('d/m/Y') }} ({{ $warranty->duration_months }} tháng)</dd>
                        </div>
                        <div class="cust-detail-item sm:col-span-2">
                            <dt>Điều khoản</dt>
                            <dd>{{ $warranty->terms ?: '-' }}</dd>
                        </div>
                    </dl>
                </div>
            </div>
            <div class="cust-actions mt-6">
                <form method="POST" action="{{ route('warranties.destroy', $warranty) }}">@csrf @method('DELETE')<button
                        class="cust-btn cust-btn-danger"
                        onclick="return confirm('Xóa phiếu bảo hành này?')">Xóa</button></form>
            </div>
        </main>
    </div>
</body>

</html>