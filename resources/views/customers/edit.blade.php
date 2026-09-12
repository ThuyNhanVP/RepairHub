<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Sửa khách hàng - {{ config('app.name', 'RepairHub') }}</title>
    @if (file_exists(public_path('build/manifest.json')))
        @vite(['resources/css/app.css', 'resources/css/login.css', 'resources/css/customers.css'])
    @endif
</head>
<body class="customers-page">
    <div class="cust-layout">
        <header class="cust-header">
            <div class="cust-header-inner">
                <h1 class="cust-title">Sửa khách hàng</h1>
                <a href="{{ route('customers.index') }}" class="cust-btn cust-btn-secondary">Quay lại</a>
            </div>
        </header>

        <main class="cust-main">
            <div class="cust-card">
                <div class="cust-card-header">
                    <h2 class="cust-title-main">Sửa khách hàng: {{ $customer->name }}</h2>
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

                    <form method="POST" action="{{ route('customers.update', $customer) }}" class="cust-form">
                        @csrf
                        @method('PUT')

                        <div>
                            <label for="name" class="cust-label">Tên <span class="text-red-400">*</span></label>
                            <input type="text"
                                   id="name"
                                   name="name"
                                   value="{{ old('name', $customer->name) }}"
                                   required
                                   class="cust-input">
                        </div>

                        <div>
                            <label for="phone" class="cust-label">Số điện thoại <span class="text-red-400">*</span></label>
                            <input type="tel"
                                   id="phone"
                                   name="phone"
                                   value="{{ old('phone', $customer->phone) }}"
                                   required
                                   class="cust-input"
                                   placeholder="09xxxxxxxx">
                        </div>

                        <div>
                            <label for="email" class="cust-label">Email</label>
                            <input type="email"
                                   id="email"
                                   name="email"
                                   value="{{ old('email', $customer->email) }}"
                                   class="cust-input"
                                   placeholder="khachhang@email.com">
                        </div>

                        <div>
                            <label for="address" class="cust-label">Địa chỉ</label>
                            <textarea id="address"
                                      name="address"
                                      rows="3"
                                      class="cust-textarea"
                                      placeholder="Địa chỉ chi tiết">{{ old('address', $customer->address) }}</textarea>
                        </div>

                        <div>
                            <label for="notes" class="cust-label">Ghi chú</label>
                            <textarea id="notes"
                                      name="notes"
                                      rows="3"
                                      class="cust-textarea"
                                      placeholder="Ghi chú thêm">{{ old('notes', $customer->notes) }}</textarea>
                        </div>

                        <div class="flex justify-end space-x-3 pt-6 border-t border-slate-700">
                            <a href="{{ route('customers.index') }}" class="cust-btn cust-btn-secondary">Hủy</a>
                            <button type="submit" class="cust-btn cust-btn-primary">Cập nhật</button>
                        </div>
                    </form>
                </div>
            </div>
        </main>
    </div>
</body>
</html>