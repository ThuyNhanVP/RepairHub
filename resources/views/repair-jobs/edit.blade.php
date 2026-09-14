<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Sửa công việc #{{ $repairJob->id }} - {{ config('app.name', 'RepairHub') }}</title>
    @if (file_exists(public_path('build/manifest.json')))
        @vite(['resources/css/app.css', 'resources/css/login.css', 'resources/css/customers.css'])
    @endif
</head>
<body class="customers-page">
    <div class="cust-layout">
        <header class="cust-header">
            <div class="cust-header-inner">
                <h1 class="cust-title">Sửa công việc #{{ $repairJob->id }}</h1>
                <a href="{{ route('repair-jobs.index') }}" class="cust-btn cust-btn-secondary">Quay lại</a>
            </div>
        </header>
        <main class="cust-main">
            <div class="cust-card">
                <div class="cust-card-header">
                    <h2 class="cust-title-main">Cập nhật công việc</h2>
                </div>
                <div class="cust-card-body">
                    @if ($errors->any())
                        <div class="cust-error">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <form method="POST" action="{{ route('repair-jobs.update', $repairJob) }}" class="cust-form">
                        @csrf
                        @method('PUT')
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="cust-label">Kỹ thuật viên</label>
                                <select name="technician_id" class="cust-input">
                                    <option value="">Chưa phân công</option>
                                    @foreach ($technicians as $tech)
                                        <option value="{{ $tech->id }}" {{ $repairJob->technician_id == $tech->id ? 'selected' : '' }}>{{ $tech->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="cust-label">Trạng thái *</label>
                                <select name="status" class="cust-input" required>
                                    @foreach(['pending'=>'Chờ xử lý','diagnosed'=>'Đã chẩn đoán','quoted'=>'Đã báo giá','approved'=>'Đã duyệt','repairing'=>'Đang sửa','waiting_parts'=>'Chờ linh kiện','completed'=>'Hoàn tất','cancelled'=>'Đã hủy'] as $v => $l)
                                        <option value="{{ $v }}" {{ $repairJob->status === $v ? 'selected' : '' }}>{{ $l }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="cust-label">Chẩn đoán</label>
                                <textarea name="diagnosis" rows="3" class="cust-textarea">{{ old('diagnosis', $repairJob->diagnosis) }}</textarea>
                            </div>
                            <div>
                                <label class="cust-label">Ghi chú</label>
                                <textarea name="notes" rows="3" class="cust-textarea">{{ old('notes', $repairJob->notes) }}</textarea>
                            </div>
                            <div>
                                <label class="cust-label">Chi phí dự kiến</label>
                                <input type="number" name="estimated_cost" value="{{ old('estimated_cost', $repairJob->estimated_cost) }}" step="1000" min="0" class="cust-input">
                            </div>
                            <div>
                                <label class="cust-label">Chi phí thực tế</label>
                                <input type="number" name="final_cost" value="{{ old('final_cost', $repairJob->final_cost) }}" step="1000" min="0" class="cust-input">
                            </div>
                        </div>
                        <div class="cust-actions">
                            <a href="{{ route('repair-jobs.show', $repairJob) }}" class="cust-btn cust-btn-secondary">Hủy</a>
                            <button type="submit" class="cust-btn cust-btn-primary">Cập nhật</button>
                        </div>
                    </form>
                </div>
            </div>
        </main>
    </div>
</body>
</html>