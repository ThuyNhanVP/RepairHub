<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Linh kiện - {{ config('app.name', 'RepairHub') }}</title>
    @if (file_exists(public_path('build/manifest.json')))
        @vite(['resources/css/app.css', 'resources/css/login.css', 'resources/css/customers.css'])
    @endif
</head>
<body class="customers-page">
    <div class="cust-layout">
        <header class="cust-header">
            <div class="cust-header-inner">
                <h1 class="cust-title">Quản lý linh kiện</h1>
                <a href="{{ route('parts.create') }}" class="cust-btn cust-btn-primary">Thêm linh kiện</a>
            </div>
        </header>

        <main class="cust-main">
            @if (session('success'))
                <div class="cust-success">{{ session('success') }}</div>
            @endif

            <form method="GET" action="{{ route('parts.index') }}" class="cust-card mb-6">
                <div class="cust-card-body flex flex-wrap gap-4">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Tìm tên, SKU, hãng..." class="cust-input flex-1 min-w-[200px]">
                    <select name="category_id" class="cust-input max-w-[200px]">
                        <option value="">Tất cả danh mục</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                        @endforeach
                    </select>
                    <label class="cust-checkbox-label">
                        <input type="checkbox" name="low_stock" value="1" {{ request('low_stock') ? 'checked' : '' }} class="cust-checkbox">
                        Sắp hết hàng
                    </label>
                    <button type="submit" class="cust-btn cust-btn-secondary">Lọc</button>
                    @if(request()->hasAny(['search', 'category_id', 'low_stock']))
                        <a href="{{ route('parts.index') }}" class="cust-btn cust-btn-ghost">Xóa lọc</a>
                    @endif
                </div>
            </form>

            <div class="cust-card">
                <div class="cust-card-header">
                    <h2 class="cust-title-main">Danh sách linh kiện</h2>
                </div>

                <div class="cust-card-body">
                    <div class="overflow-x-auto">
                        <table class="cust-table">
                            <thead>
                                <tr>
                                    <th>SKU</th>
                                    <th>Tên</th>
                                    <th>Danh mục</th>
                                    <th>Tồn kho</th>
                                    <th>Giá bán</th>
                                    <th>Trạng thái</th>
                                    <th class="text-right">Thao tác</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y" style="border-color: var(--color-cust-border);">
                                @forelse ($parts as $part)
                                    <tr class="cust-hover-row">
                                        <td class="font-medium">{{ $part->sku }}</td>
                                        <td>{{ $part->name }}</td>
                                        <td>{{ $part->category->name ?? '-' }}</td>
                                        <td>
                                            {{ $part->stock_qty }} {{ $part->unit }}
                                            @if($part->isLowStock())
                                                <span class="cust-badge" style="background: var(--color-cust-danger); color: white;">Sắp hết</span>
                                            @endif
                                        </td>
                                        <td>{{ number_format($part->sale_price, 0, ',', '.') }} VNĐ</td>
                                        <td><span class="cust-badge">{{ $part->is_active ? 'Hoạt động' : 'Ẩn' }}</span></td>
                                        <td class="text-right">
                                            <a href="{{ route('parts.show', $part) }}" class="cust-link mr-4">Xem</a>
                                            <a href="{{ route('parts.edit', $part) }}" class="cust-link mr-4">Sửa</a>
                                            <form action="{{ route('parts.destroy', $part) }}" method="POST" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" onclick="return confirm('Bạn có chắc chắn muốn xóa?')" class="text-red-400 hover:text-red-300">Xóa</button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="cust-empty">Chưa có linh kiện nào. <a href="{{ route('parts.create') }}" class="cust-link">Tạo đầu tiên</a></td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="cust-pagination mt-6">
                        {{ $parts->links() }}
                    </div>
                </div>
            </div>
        </main>
    </div>
</body>
</html>