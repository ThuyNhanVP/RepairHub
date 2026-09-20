<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $warrantyClaim->claim_code }} - Bảo hành</title>@if (file_exists(public_path('build/manifest.json')))
    @vite(['resources/css/app.css', 'resources/css/login.css', 'resources/css/customers.css']) @endif
</head>

<body class="customers-page">
    <div class="cust-layout">
        <header class="cust-header">
            <div class="cust-header-inner">
                <h1 class="cust-title">Yêu cầu {{ $warrantyClaim->claim_code }}</h1>
                <div class="flex gap-2"><a href="{{ route('warranty-claims.edit', $warrantyClaim) }}"
                        class="cust-btn cust-btn-primary">Sửa</a><a href="{{ route('warranty-claims.index') }}"
                        class="cust-btn cust-btn-secondary">Quay lại</a></div>
            </div>
        </header>
        <main class="cust-main">@if (session('success'))
        <div class="cust-success">{{ session('success') }}</div>@endif<div class="cust-card">
                <div class="cust-card-header">
                    <h2 class="cust-title-main">Thông tin yêu cầu</h2>
                </div>
                <div class="cust-card-body">
                    <dl class="cust-detail-grid">
                        <div class="cust-detail-item">
                            <dt>Khách hàng</dt>
                            <dd>{{ $warrantyClaim->warranty->customer->name }} -
                                {{ $warrantyClaim->warranty->customer->phone }}</dd>
                        </div>
                        <div class="cust-detail-item">
                            <dt>Thiết bị</dt>
                            <dd>{{ $warrantyClaim->warranty->device->brand }}
                                {{ $warrantyClaim->warranty->device->model }}</dd>
                        </div>
                        <div class="cust-detail-item">
                            <dt>Phiếu bảo hành</dt>
                            <dd><a class="cust-link"
                                    href="{{ route('warranties.show', $warrantyClaim->warranty) }}">{{ $warrantyClaim->warranty->warranty_code }}</a>
                            </dd>
                        </div>
                        <div class="cust-detail-item">
                            <dt>Trạng thái</dt>
                            <dd><span class="cust-badge">{{ $warrantyClaim->status }}</span></dd>
                        </div>
                        <div class="cust-detail-item">
                            <dt>Tiếp nhận</dt>
                            <dd>{{ $warrantyClaim->received_at->format('d/m/Y H:i') }}</dd>
                        </div>
                        <div class="cust-detail-item sm:col-span-2">
                            <dt>Mô tả lỗi</dt>
                            <dd>{{ $warrantyClaim->issue }}</dd>
                        </div>
                        <div class="cust-detail-item sm:col-span-2">
                            <dt>Kết quả xử lý</dt>
                            <dd>{{ $warrantyClaim->resolution ?: '-' }}</dd>
                        </div>
                        <div class="cust-detail-item sm:col-span-2">
                            <dt>Ghi chú</dt>
                            <dd>{{ $warrantyClaim->notes ?: '-' }}</dd>
                        </div>
                    </dl>
                </div>
            </div>
            <div class="cust-actions mt-6">
                <form method="POST" action="{{ route('warranty-claims.destroy', $warrantyClaim) }}">@csrf
                    @method('DELETE')<button class="cust-btn cust-btn-danger"
                        onclick="return confirm('Xóa yêu cầu bảo hành này?')">Xóa</button></form>
            </div>
        </main>
    </div>
</body>

</html>