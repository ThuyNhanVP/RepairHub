<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Công việc #{{ $repairJob->id }} - {{ config('app.name', 'RepairHub') }}</title>
    @if (file_exists(public_path('build/manifest.json')))
        @vite(['resources/css/app.css', 'resources/css/login.css', 'resources/css/customers.css'])
    @endif
</head>
<body class="customers-page">
    <div class="cust-layout">
        <header class="cust-header">
            <div class="cust-header-inner">
                <h1 class="cust-title">Công việc sửa chữa #{{ $repairJob->id }}</h1>
                <div class="flex items-center space-x-2">
                    <a href="{{ route('repair-jobs.edit', $repairJob) }}" class="cust-btn cust-btn-primary">Sửa</a>
                    <a href="{{ route('repair-jobs.index') }}" class="cust-btn cust-btn-secondary">Quay lại</a>
                </div>
            </div>
        </header>
        <main class="cust-main">
            @if (session('success'))
                <div class="cust-success">{{ session('success') }}</div>
            @endif
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <div class="cust-card">
                    <div class="cust-card-header">
                        <h2 class="cust-title-main">Thông tin chung</h2>
                    </div>
                    <div class="cust-card-body">
                        <dl class="cust-detail-grid">
                            <div class="cust-detail-item">
                                <dt>Khách hàng</dt>
                                <dd>{{ $repairJob->reception->customer->name ?? '-' }} - {{ $repairJob->reception->customer->phone ?? '-' }}</dd>
                            </div>
                            <div class="cust-detail-item">
                                <dt>Thiết bị</dt>
                                <dd>{{ $repairJob->reception->device->brand ?? '' }} {{ $repairJob->reception->device->model ?? '' }} ({{ $repairJob->reception->device->device_type ?? '' }})</dd>
                            </div>
                            <div class="cust-detail-item">
                                <dt>Trạng thái</dt>
                                <dd><span class="cust-badge">{{ $repairJob->status }}</span></dd>
                            </div>
                            <div class="cust-detail-item">
                                <dt>Kỹ thuật viên</dt>
                                <dd>{{ $repairJob->technician->name ?? '-' }}</dd>
                            </div>
                            <div class="cust-detail-item">
                                <dt>Chẩn đoán hiện tại</dt>
                                <dd>{{ $repairJob->diagnosis ?? '-' }}</dd>
                            </div>
                            <div class="cust-detail-item">
                                <dt>Chi phí</dt>
                                <dd>{{ $repairJob->estimated_cost ? number_format($repairJob->estimated_cost, 0, ',', '.') . ' VNĐ' : '-' }} / {{ $repairJob->final_cost ? number_format($repairJob->final_cost, 0, ',', '.') . ' VNĐ' : '-' }}</dd>
                            </div>
                            <div class="cust-detail-item sm:col-span-2">
                                <dt>Ghi chú</dt>
                                <dd>{{ $repairJob->notes ?? '-' }}</dd>
                            </div>
                        </dl>
                    </div>
                </div>

                <div class="cust-card">
                    <div class="cust-card-header">
                        <h2 class="cust-title-main">Thêm nhật ký (Repair Step)</h2>
                    </div>
                    <div class="cust-card-body">
                        <form method="POST" action="{{ route('repair-jobs.add-step', $repairJob) }}" class="cust-form">
                            @csrf
                            <div>
                                <label class="cust-label">Loại</label>
                                <select name="step_type" class="cust-input" required>
                                    <option value="diagnosis">Chẩn đoán</option>
                                    <option value="quote">Báo giá</option>
                                    <option value="approval">Xác nhận</option>
                                    <option value="repair_note">Ghi sửa chữa</option>
                                    <option value="part_used">Dùng linh kiện</option>
                                    <option value="completion">Hoàn tất</option>
                                </select>
                            </div>
                            <div>
                                <label class="cust-label">Tiêu đề</label>
                                <input type="text" name="title" class="cust-input" required placeholder="Ví dụ: Thay pin...">
                            </div>
                            <div>
                                <label class="cust-label">Nội dung</label>
                                <textarea name="content" rows="3" class="cust-textarea" placeholder="Mô tả chi tiết..."></textarea>
                            </div>
                            <div>
                                <label class="cust-label">Chi phí (VNĐ)</label>
                                <input type="number" name="cost" step="1000" min="0" class="cust-input">
                            </div>
                            <div class="cust-actions">
                                <button type="submit" class="cust-btn cust-btn-primary">Thêm bước</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="cust-card mt-6">
                <div class="cust-card-header">
                    <h2 class="cust-title-main">Nhật ký sửa chữa</h2>
                </div>
                <div class="cust-card-body">
                    @forelse ($repairJob->steps as $step)
                        <div class="cust-card mb-3">
                            <div class="cust-card-body">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <div class="cust-title-main text-sm">{{ $step->title }} <span class="cust-badge ml-2">{{ $step->step_type }}</span></div>
                                        <div class="text-sm mt-1" style="color: var(--color-cust-muted);">{{ $step->content ?? '-' }}</div>
                                        <div class="text-xs mt-1" style="color: var(--color-cust-muted);">{{ $step->performed_at->format('d/m/Y H:i') }} - {{ $step->user->name ?? '-' }} {{ $step->cost ? ' - ' . number_format($step->cost, 0, ',', '.') . ' VNĐ' : '' }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="cust-empty">Chưa có nhật ký nào.</div>
                    @endforelse
                </div>
            </div>

            <div class="cust-actions mt-6">
                <a href="{{ route('repair-jobs.edit', $repairJob) }}" class="cust-btn cust-btn-primary">Sửa công việc</a>
                <form action="{{ route('repair-jobs.destroy', $repairJob) }}" method="POST" class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" onclick="return confirm('Bạn có chắc chắn muốn xóa công việc này?')" class="cust-btn cust-btn-danger">Xóa</button>
                </form>
                <a href="{{ route('repair-jobs.index') }}" class="cust-btn cust-btn-secondary">Danh sách</a>
            </div>
        </main>
    </div>
</body>
</html>
