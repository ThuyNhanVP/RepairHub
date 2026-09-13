<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Tiếp nhận mới - {{ config('app.name', 'RepairHub') }}</title>
    @if (file_exists(public_path('build/manifest.json')))
        @vite(['resources/css/app.css', 'resources/css/login.css', 'resources/css/customers.css'])
    @endif
</head>
<body class="customers-page">
    <div class="cust-layout">
        <header class="cust-header">
            <div class="cust-header-inner">
                <h1 class="cust-title">Tiếp nhận thiết bị mới</h1>
                <a href="{{ route('receptions.index') }}" class="cust-btn cust-btn-secondary">Quay lại</a>
            </div>
        </header>

        <main class="cust-main">
            <div class="cust-card">
                <div class="cust-card-header">
                    <h2 class="cust-title-main">Tạo phiếu tiếp nhận mới</h2>
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

                    <form method="POST" action="{{ route('receptions.store') }}" class="cust-form">
                        @csrf

                        <div class="cust-card">
                            <div class="cust-card-header">
                                <h3 class="cust-title-main">Thông tin khách hàng</h3>
                            </div>
                            <div class="cust-card-body">
                                <div>
                                    <label for="customer_id" class="cust-label">Khách hàng <span class="text-red-400">*</span></label>
                                    <select id="customer_id"
                                            name="customer_id"
                                            required
                                            class="cust-input">
                                        <option value="">Chọn khách hàng</option>
                                        @foreach ($customers as $customer)
                                            <option value="{{ $customer->id }}"
                                                {{ old('customer_id') == $customer->id ? 'selected' : '' }}>
                                                {{ $customer->name }} - {{ $customer->phone }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="cust-card mt-6">
                            <div class="cust-card-header">
                                <h3 class="cust-title-main">Thông tin thiết bị</h3>
                            </div>
                            <div class="cust-card-body">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label for="brand" class="cust-label">Hãng</label>
                                        <input type="text"
                                               id="brand"
                                               name="brand"
                                               value="{{ old('brand') }}"
                                               class="cust-input"
                                               placeholder="Ví dụ: Apple, Samsung, Dell...">
                                    </div>

                                    <div>
                                        <label for="model" class="cust-label">Model</label>
                                        <input type="text"
                                               id="model"
                                               name="model"
                                               value="{{ old('model') }}"
                                               class="cust-input"
                                               placeholder="Ví dụ: iPhone 15 Pro, Galaxy S24...">
                                    </div>

                                    <div>
                                        <label for="serial_number" class="cust-label">Serial Number</label>
                                        <input type="text"
                                               id="serial_number"
                                               name="serial_number"
                                               value="{{ old('serial_number') }}"
                                               class="cust-input"
                                               placeholder="Serial number của thiết bị">
                                    </div>

                                    <div>
                                        <label for="imei" class="cust-label">IMEI</label>
                                        <input type="text"
                                               id="imei"
                                               name="imei"
                                               value="{{ old('imei') }}"
                                               class="cust-input"
                                               placeholder="IMEI (nếu có)">
                                    </div>

                                    <div>
                                        <label for="device_type" class="cust-label">Loại thiết bị</label>
                                        <select id="device_type"
                                                name="device_type"
                                                class="cust-input">
                                            <option value="">Chọn loại</option>
                                            <option value="phone" {{ old('device_type') === 'phone' ? 'selected' : '' }}>Điện thoại</option>
                                            <option value="laptop" {{ old('device_type') === 'laptop' ? 'selected' : '' }}>Laptop</option>
                                            <option value="tablet" {{ old('device_type') === 'tablet' ? 'selected' : '' }}>Máy tính bảng</option>
                                            <option value="watch" {{ old('device_type') === 'watch' ? 'selected' : '' }}>Đồng hồ thông minh</option>
                                            <option value="other" {{ old('device_type') === 'other' ? 'selected' : '' }}>Khác</option>
                                        </select>
                                    </div>

                                    <div>
                                        <label for="color" class="cust-label">Màu sắc</label>
                                        <input type="text"
                                               id="color"
                                               name="color"
                                               value="{{ old('color') }}"
                                               class="cust-input"
                                               placeholder="Ví dụ: Đen, Trắng, Xám...">
                                    </div>
                                </div>

                                <div>
                                    <label for="device_notes" class="cust-label">Ghi chú thiết bị</label>
                                    <textarea id="device_notes"
                                              name="device_notes"
                                              rows="3"
                                              class="cust-textarea"
                                              placeholder="Tình trạng bên ngoài, phụ kiện kèm theo...">{{ old('device_notes') }}</textarea>
                                </div>
                            </div>
                        </div>

                        <div class="cust-card mt-6">
                            <div class="cust-card-header">
                                <h3 class="cust-title-main">Mô tả sự cố</h3>
                            </div>
                            <div class="cust-card-body">
                                <div>
                                    <label for="description" class="cust-label">Mô tả sự cố từ khách hàng <span class="text-red-400">*</span></label>
                                    <textarea id="description"
                                              name="description"
                                              required
                                              rows="4"
                                              class="cust-textarea"
                                              placeholder="Khách hàng mô tả sự cố: ví dụ - màn hình vỡ, không sạc được, nghe kêu lạ...">{{ old('description') }}</textarea>
                                </div>

                                <div>
                                    <label for="notes" class="cust-label">Ghi chú nội bộ</label>
                                    <textarea id="notes"
                                              name="notes"
                                              rows="3"
                                              class="cust-textarea"
                                              placeholder="Ghi chú cho nhân viên kỹ thuật...">{{ old('notes') }}</textarea>
                                </div>
                            </div>
                        </div>

                        <div class="flex justify-end space-x-3 pt-6 border-t border-slate-700">
                            <a href="{{ route('receptions.index') }}" class="cust-btn cust-btn-secondary">Hủy</a>
                            <button type="submit" class="cust-btn cust-btn-primary">Tạo phiếu tiếp nhận</button>
                        </div>
                    </form>
                </div>
            </div>
        </main>
    </div>
</body>
</html>
