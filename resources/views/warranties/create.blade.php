<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Tạo bảo hành - {{ config('app.name', 'RepairHub') }}</title>
    @if (file_exists(public_path('build/manifest.json'))) @vite(['resources/css/app.css', 'resources/css/login.css', 'resources/css/customers.css']) @endif
</head>
<body class="customers-page">
    <div class="cust-layout"><header class="cust-header"><div class="cust-header-inner"><h1 class="cust-title">Tạo phiếu bảo hành</h1><a href="{{ route('warranties.index') }}" class="cust-btn cust-btn-secondary">Quay lại</a></div></header>
        <main class="cust-main"><div class="cust-card"><div class="cust-card-body">
            @if ($errors->any()) <div class="cust-error"><ul>@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div> @endif
            <form method="POST" action="{{ route('warranties.store') }}" class="cust-form">@csrf
                <div><label class="cust-label">Công việc đã hoàn tất *</label><select name="repair_job_id" class="cust-input" required><option value="">-- Chọn công việc --</option>@foreach ($repairJobs as $repairJob)<option value="{{ $repairJob->id }}" @selected(old('repair_job_id') == $repairJob->id)>#{{ $repairJob->id }} - {{ $repairJob->reception->customer->name }} - {{ $repairJob->reception->device->brand }} {{ $repairJob->reception->device->model }}</option>@endforeach</select></div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div><label class="cust-label">Mã bảo hành</label><input name="warranty_code" value="{{ old('warranty_code') }}" class="cust-input" placeholder="Tự động nếu bỏ trống"></div>
                    <div><label class="cust-label">Ngày bắt đầu *</label><input type="date" name="start_date" value="{{ old('start_date', now()->toDateString()) }}" class="cust-input" required></div>
                    <div><label class="cust-label">Thời hạn (tháng) *</label><input type="number" name="duration_months" value="{{ old('duration_months', 3) }}" min="1" max="120" class="cust-input" required></div>
                    <div><label class="cust-label">Trạng thái *</label><select name="status" class="cust-input" required><option value="active">Còn hạn</option><option value="void">Đã hủy</option></select></div>
                </div>
                <div><label class="cust-label">Điều khoản</label><textarea name="terms" rows="4" class="cust-textarea">{{ old('terms', 'Bảo hành lỗi kỹ thuật trong thời hạn bảo hành.') }}</textarea></div>
                <div class="cust-actions"><a href="{{ route('warranties.index') }}" class="cust-btn cust-btn-secondary">Hủy</a><button class="cust-btn cust-btn-primary">Tạo bảo hành</button></div>
            </form>
        </div></div></main>
    </div>
</body>
</html>
