<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $part->name }} - {{ config('app.name', 'RepairHub') }}</title>
    @if (file_exists(public_path('build/manifest.json')))
        @vite(['resources/css/app.css', 'resources/css/login.css', 'resources/css/customers.css'])
    @endif
</head>
<body class="customers-page">
    <div class="cust-layout">
        <header class="cust-header">
            <div class="cust-header-inner">
                <h1 class="cust-title">{{ $part->name }} ({{ $part->sku }})</h1>
                <div class="flex items-center space-x-2">
                    <a href="{{ route('parts.edit', $part) }}" class="cust-btn cust-btn-primary">Sửa</a>
                    <a href="{{ route('parts.index') }}" class="cust-btn cust-btn-secondary">Quay lại</a>
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
                        <h2 class="cust-title-main">Thông tin linh kiện</h2>
                    </div>
                    <div class="cust-card-body">
                        <dl class="cust-detail-grid">
                            <div class="cust-detail-item"><dt>SKU</dt><dd>{{ $part->sku }}</dd></div>
                            <div class="cust-detail-item"><dt>Danh mục</dt><dd>{{ $part->category->name ?? '-' }}</dd></div>
                            <div class="cust-detail-item"><dt>Hãng</dt><dd>{{ $part->brand ?? '-' }}</dd></div>
                            <div class="cust-detail-item"><dt>Đơn vị</dt><dd>{{ $part->unit }}</dd></div>
                            <div class="cust-detail-item"><dt>Giá nhập</dt><dd>{{ number_format($part->cost_price, 0, ',', '.') }} VNĐ</dd></div>
                            <div class="cust-detail-item"><dt>Giá bán</dt><dd>{{ number_format($part->sale_price, 0, ',', '.') }} VNĐ</dd></div>
                            <div class="cust-detail-item"><dt>Tồn kho</dt><dd>{{ $part->stock_qty }} {{ $part->unit }} @if($part->isLowStock()) <span class="cust-badge" style="background: var(--color-cust-danger); color: white;">Sắp hết</span> @endif</dd></div>
                            <div class="cust-detail-item"><dt>Mức cảnh báo</dt><dd>{{ $part->min_stock_qty }}</dd></div>
                            <div class="cust-detail-item"><dt>Vị trí kho</dt><dd>{{ $part->location ?? '-' }}</dd></div>
                            <div class="cust-detail-item"><dt>Trạng thái</dt><dd>{{ $part->is_active ? 'Hoạt động' : 'Ẩn' }}</dd></div>
                            <div class="cust-detail-item sm:col-span-2"><dt>Mô tả</dt><dd>{{ $part->description ?? '-' }}</dd></div>
                        </dl>
                    </div>
                </div>
                <div class="cust-card">
                    <div class="cust-card-header">
                        <h2 class="cust-title-main">Nhập/Xuất kho</h2>
                    </div>
                    <div class="cust-card-body space-y-6">
                        <form method="POST" action="{{ route('parts.add-stock', $part) }}" class="cust-form">
                            @csrf
                            <h3 class="cust-label">Nhập kho</h3>
                            <div class="grid grid-cols-2 gap-2">
                                <input type="number" name="qty" min="1" required class="cust-input" placeholder="Số lượng">
                                <input type="number" name="unit_cost" step="0.01" min="0" class="cust-input" placeholder="Giá/đv (tùy chọn)">
                            </div>
                            <input type="text" name="reference_number" class="cust-input" placeholder="Mã phiếu (PO-...)">
                            <textarea name="notes" rows="2" class="cust-textarea" placeholder="Ghi chú..."></textarea>
                            <div class="cust-actions"><button type="submit" class="cust-btn cust-btn-primary w-full">Nhập kho</button></div>
                        </form>
                        <form method="POST" action="{{ route('parts.remove-stock', $part) }}" class="cust-form">
                            @csrf
                            <h3 class="cust-label">Xuất kho</h3>
                            <div class="grid grid-cols-2 gap-2">
                                <input type="number" name="qty" min="1" max="{{ $part->stock_qty }}" required class="cust-input" placeholder="Số lượng">
                                <input type="text" name="reference_number" class="cust-input" placeholder="Mã phiếu">
                            </div>
                            <textarea name="notes" rows="2" class="cust-textarea" placeholder="Ghi chú..."></textarea>
                            <div class="cust-actions"><button type="submit" class="cust-btn cust-btn-danger w-full">Xuất kho</button></div>
                        </form>
                        <form method="POST" action="{{ route('parts.adjust-stock', $part) }}" class="cust-form">
                            @csrf
                            <h3 class="cust-label">Điều chỉnh tồn kho</h3>
                            <input type="number" name="qty" min="0" required class="cust-input" placeholder="Tồn kho mới">
                            <textarea name="notes" rows="2" class="cust-textarea" placeholder="Lý do điều chỉnh..."></textarea>
                            <div class="cust-actions"><button type="submit" class="cust-btn cust-btn-secondary w-full">Điều chỉnh</button></div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="cust-card mt-6">
                <div class="cust-card-header">
                    <h2 class="cust-title-main">Lịch sử xuất nhập</h2>
                </div>
                <div class="cust-card-body">
                    @forelse ($part->stockMovements as $movement)
                        <div class="cust-card mb-3">
                            <div class="cust-card-body">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <div class="cust-title-main text-sm"><span class="cust-badge">{{ $movement->type_label }}</span> - {{ $movement->qty }} {{ $part->unit }}</div>
                                        <div class="text-sm mt-1" style="color: var(--color-cust-muted);">{{ $movement->reference_number ?? '-' }} {{ $movement->notes ?? '' }}</div>
                                        <div class="text-xs mt-1" style="color: var(--color-cust-muted);">{{ $movement->performed_at->format('d/m/Y H:i') }} - {{ $movement->user->name ?? '-' }} {{ $movement->unit_cost ? ' - ' . number_format($movement->unit_cost, 0, ',', '.') . ' VNĐ' : '' }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="cust-empty">Chưa có giao dịch nào.</div>
                    @endforelse
                </div>
            </div>
            <div class="cust-actions mt-6">
                <a href="{{ route('parts.edit', $part) }}" class="cust-btn cust-btn-primary">Sửa linh kiện</a>
                <form action="{{ route('parts.destroy', $part) }}" method="POST" class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" onclick="return confirm('Bạn có chắc chắn muốn xóa?')" class="cust-btn cust-btn-danger">Xóa</button>
                </form>
                <a href="{{ route('parts.index') }}" class="cust-btn cust-btn-secondary">Danh sách</a>
            </div>
        </main>
    </div>
</body>
</html>
