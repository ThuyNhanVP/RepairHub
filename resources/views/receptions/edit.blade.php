<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Sửa phiếu #{{ $reception->id }} - {{ config('app.name', 'RepairHub') }}</title>
    @if (file_exists(public_path('build/manifest.json')))
        @vite(['resources/css/app.css', 'resources/css/login.css', 'resources/css/customers.css'])
    @endif
</head>
<body class="customers-page">
    <div class="cust-layout">
        <header class="cust-header">
            <div class="cust-header-inner">
                <h1 class="cust-title">Sửa phiếu tiếp nhận #{{ $reception->id }}</h1>
                <a href="{{ route('receptions.index') }}" class="cust-btn cust-btn-secondary">Quay lại</a>
            </div>
        </header>
        <main class="cust-main">
            <div class="cust-card">
                <div class="cust-card-header">
                    <h2 class="cust-title-main">Cập nhật trạng thái & chi phí</h2>
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

                    <form method="POST" action="{{ route('receptions.update', $reception) }}" class="cust-form">
                        @csrf
                        @method('PUT')
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="status" class="cust-label">Trạng thái <span class="text-red-400">*</span></label>
                                <select id="status" name="status" required class="cust-input">
                                    @foreach(['received'=>'Đã tiếp nhận','diagnosing'=>'Đang chẩn đoán','quoted'=>'Đã báo giá','waiting_approval'=>'Chờ khách duyệt','repairing'=>'Đang sửa chữa','waiting_parts'=>'Chờ linh kiện','completed'=>'Đã sửa xong','delivered'=>'Đã bàn giao','cancelled'=>'Đã hủy'] as $value => $label)
                                        <option value="{{ $value }}" {{ $reception->status === $value ? 'selected' : '' }}>{{ $label }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label for="diagnosis" class="cust-label">Chẩn đoán</label>
                                <textarea id="diagnosis" name="diagnosis" rows="3" class="cust-textarea" placeholder="Kết quả chẩn đoán...">{{ old('diagnosis', $reception->diagnosis) }}</textarea>
                            </div>
                            <div>
                                <label for="estimated_cost" class="cust-label">Chi phí dự kiến (VNĐ)</label>
                                <input type="number" id="estimated_cost" name="estimated_cost" value="{{ old('estimated_cost', $reception->estimated_cost) }}" step="1000" min="0" class="cust-input">
                            </div>
                            <div>
                                <label for="final_cost" class="cust-label">Chi phí thực tế (VNĐ)</label>
                                <input type="number" id="final_cost" name="final_cost" value="{{ old('final_cost', $reception->final_cost) }}" step="1000" min="0" class="cust-input">
                            </div>
                            <div>
                                <label for="estimated_completion_at" class="cust-label">Dự kiến hoàn thành</label>
                                <input type="date" id="estimated_completion_at" name="estimated_completion_at" value="{{ old('estimated_completion_at', $reception->estimated_completion_at?->format('Y-m-d')) }}" class="cust-input">
                            </div>
                            <div>
                                <label for="completed_at" class="cust-label">Ngày hoàn thành</label>
                                <input type="date" id="completed_at" name="completed_at" value="{{ old('completed_at', $reception->completed_at?->format('Y-m-d')) }}" class="cust-input">
                            </div>
                            <div>
                                <label for="delivered_at" class="cust-label">Ngày bàn giao</label>
                                <input type="date" id="delivered_at" name="delivered_at" value="{{ old('delivered_at', $reception->delivered_at?->format('Y-m-d')) }}" class="cust-input">
                            </div>
                            <div>
                                <label for="notes" class="cust-label">Ghi chú</label>
                                <textarea id="notes" name="notes" rows="3" class="cust-textarea" placeholder="Ghi chú thêm...">{{ old('notes', $reception->notes) }}</textarea>
                            </div>
                        </div>
                        <div class="cust-actions">
                            <a href="{{ route('receptions.show', $reception) }}" class="cust-btn cust-btn-secondary">Hủy</a>
                            <button type="submit" class="cust-btn cust-btn-primary">Cập nhật</button>
                        </div>
                    </form>
                </div>
            </div>
        </main>
    </div>
</body>
</html>