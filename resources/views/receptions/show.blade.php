<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Phiếu #{{ $reception->id }} - {{ config('app.name', 'RepairHub') }}</title>
    @if (file_exists(public_path('build/manifest.json')))
        @vite(['resources/css/app.css', 'resources/css/login.css', 'resources/css/customers.css'])
    @endif
</head>
<body class="customers-page">
    <div class="cust-layout">
        <header class="cust-header">
            <div class="cust-header-inner">
                <h1 class="cust-title">Phiếu tiếp nhận #{{ $reception->id }}</h1>
                <div class="flex items-center space-x-2">
                    <a href="{{ route('receptions.edit', $reception) }}" class="cust-btn cust-btn-primary">Sửa</a>
                    <a href="{{ route('receptions.index') }}" class="cust-btn cust-btn-secondary">Quay lại</a>
                </div>
            </div>
        </header>

        <main class="cust-main">
            <div class="cust-card">
                <div class="cust-card-header flex items-center justify-between">
                    <h2 class="cust-title-main">Phiếu tiếp nhận #{{ $reception->id }}</h2>
                    <span class="cust-badge">{{ $reception->status }}</span>
                </div>

                <div class="cust-card-body">
                    <div class="cust-detail-grid">
                        <div class="cust-detail-item">
                            <dt>Khách hàng</dt>
                            <dd>{{ $reception->customer->name ?? '-' }} - {{ $reception->customer->phone ?? '-' }}</dd>
                        </div>

                        <div class="cust-detail-item">
                            <dt>Thiết bị</dt>
                            <dd>{{ $reception->device->brand ?? '' }} {{ $reception->device->model ?? '' }} ({{ $reception->device->device_type ?? '' }})</dd>
                        </div>

                        <div class="cust-detail-item">
                            <dt>Serial / IMEI</dt>
                            <dd>{{ $reception->device->serial_number ?? '-' }} / {{ $reception->device->imei ?? '-' }}</dd>
                        </div>

                        <div class="cust-detail-item">
                            <dt>Nhân viên tiếp nhận</dt>
                            <dd>{{ $reception->user->name ?? '-' }}</dd>
                        </div>

                        <div class="cust-detail-item">
                            <dt>Trạng thái</dt>
                            <dd><span class="cust-badge">{{ $reception->status }}</span></dd>
                        </div>

                        <div class="cust-detail-item">
                            <dt>Ngày nhận</dt>
                            <dd>{{ $reception->received_at->format('d/m/Y H:i') }}</dd>
                        </div>

                        <div class="cust-detail-item">
                            <dt>Dự kiến hoàn thành</dt>
                            <dd>{{ $reception->estimated_completion_at?->format('d/m/Y') ?? '-' }}</dd>
                        </div>

                        <div class="cust-detail-item">
                            <dt>Chi phí dự kiến</dt>
                            <dd>{{ $reception->estimated_cost ? number_format($reception->estimated_cost, 0, ',', '.') . ' VNĐ' : '-' }}</dd>
                        </div>

                        <div class="cust-detail-item">
                            <dt>Chi phí thực tế</dt>
                            <dd>{{ $reception->final_cost ? number_format($reception->final_cost, 0, ',', '.') . ' VNĐ' : '-' }}</dd>
                        </div>

                        <div class="cust-detail-item sm:col-span-2">
                            <dt>Mô tả sự cố</dt>
                            <dd>{{ $reception->description ?? '-' }}</dd>
                        </div>

                        <div class="cust-detail-item sm:col-span-2">
                            <dt>Chẩn đoán</dt>
                            <dd>{{ $reception->diagnosis ?? '-' }}</dd>
                        </div>

                        <div class="cust-detail-item sm:col-span-2">
                            <dt>Ghi chú</dt>
                            <dd>{{ $reception->notes ?? '-' }}</dd>
                        </div>
                    </dl>

                    <div class="cust-actions mt-8">
                        <a href="{{ route('receptions.edit', $reception) }}" class="cust-btn cust-btn-primary">Sửa</a>
                        <form action="{{ route('receptions.destroy', $reception) }}" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                    onclick="return confirm('Bạn có chắc chắn muốn xóa phiếu này?')"
                                    class="cust-btn cust-btn-danger">Xóa</button>
                        </form>
                        <a href="{{ route('receptions.index') }}" class="cust-btn cust-btn-secondary">Quay lại danh sách</a>
                    </div>
                </div>
            </div>
        </main>
    </div>
</body>
</html>