<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Sửa: {{ $part->name }} - {{ config('app.name', 'RepairHub') }}</title>
    @if (file_exists(public_path('build/manifest.json')))
        @vite(['resources/css/app.css', 'resources/css/login.css', 'resources/css/customers.css'])
    @endif
</head>
<body class="customers-page">
    <div class="cust-layout">
        <header class="cust-header">
            <div class="cust-header-inner">
                <h1 class="cust-title">Sửa linh kiện: {{ $part->name }}</h1>
                <a href="{{ route('parts.index') }}" class="cust-btn cust-btn-secondary">Quay lại</a>
            </div>
        </header>
        <main class="cust-main">
            <div class="cust-card">
                <div class="cust-card-header">
                    <h2 class="cust-title-main">Sửa linh kiện #{{ $part->id }}</h2>
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
                    <form method="POST" action="{{ route('parts.update', $part) }}" class="cust-form">
                        @csrf
                        @method('PUT')
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="cust-label">SKU *</label>
                                <input type="text" name="sku" value="{{ old('sku', $part->sku) }}" required class="cust-input">
                            </div>
                            <div>
                                <label class="cust-label">Tên *</label>
                                <input type="text" name="name" value="{{ old('name', $part->name) }}" required class="cust-input">
                            </div>
                            <div class="md:col-span-2">
                                <label class="cust-label">Mô tả</label>
                                <textarea name="description" rows="3" class="cust-textarea">{{ old('description', $part->description) }}</textarea>
                            </div>
                            <div>
                                <label class="cust-label">Danh mục</label>
                                <select name="category_id" class="cust-input">
                                    <option value="">-- Chọn danh mục --</option>
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}" {{ old('category_id', $part->category_id) == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="cust-label">Hãng</label>
                                <input type="text" name="brand" value="{{ old('brand', $part->brand) }}" class="cust-input">
                            </div>
                            <div>
                                <label class="cust-label">Đơn vị</label>
                                <input type="text" name="unit" value="{{ old('unit', $part->unit) }}" class="cust-input">
                            </div>
                            <div>
                                <label class="cust-label">Vị trí kho</label>
                                <input type="text" name="location" value="{{ old('location', $part->location) }}" class="cust-input">
                            </div>
                            <div>
                                <label class="cust-label">Giá nhập *</label>
                                <input type="number" name="cost_price" value="{{ old('cost_price', $part->cost_price) }}" required step="0.01" min="0" class="cust-input">
                            </div>
                            <div>
                                <label class="cust-label">Giá bán *</label>
                                <input type="number" name="sale_price" value="{{ old('sale_price', $part->sale_price) }}" required step="0.01" min="0" class="cust-input">
                            </div>
                            <div>
                                <label class="cust-label">Tồn kho *</label>
                                <input type="number" name="stock_qty" value="{{ old('stock_qty', $part->stock_qty) }}" required min="0" class="cust-input">
                            </div>
                            <div>
                                <label class="cust-label">Mức cảnh báo *</label>
                                <input type="number" name="min_stock_qty" value="{{ old('min_stock_qty', $part->min_stock_qty) }}" required min="0" class="cust-input">
                            </div>
                            <div>
                                <label class="cust-checkbox-label">
                                    <input type="checkbox" name="is_active" value="1" {{ old('is_active', $part->is_active) ? 'checked' : '' }} class="cust-checkbox">
                                    Đang hoạt động
                                </label>
                            </div>
                        </div>
                        <div class="cust-actions">
                            <a href="{{ route('parts.index') }}" class="cust-btn cust-btn-secondary">Hủy</a>
                            <button type="submit" class="cust-btn cust-btn-primary">Cập nhật</button>
                        </div>
                    </form>
                </div>
            </div>
        </main>
    </div>
</body>
</html>
