<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Sửa khách hàng - {{ config('app.name', 'RepairHub') }}</title>
    @if (file_exists(public_path('build/manifest.json')))
        @vite(['resources/css/app.css', 'resources/css/login.css'])
    @endif
</head>
<body class="bg-slate-50 min-h-screen">
    <div class="min-h-screen flex flex-col">
        <header class="bg-white border-b border-slate-200 px-6 py-4">
            <div class="max-w-2xl mx-auto flex items-center justify-between">
                <h1 class="text-2xl font-semibold text-slate-900">Sửa khách hàng</h1>
                <a href="{{ route('customers.index') }}"
                   class="px-4 py-2 text-slate-600 hover:text-slate-900">Quay lại</a>
            </div>
        </header>

        <main class="flex-1 max-w-2xl w-full mx-auto px-6 py-8">
            @if ($errors->any())
                <div class="mb-6 p-4 bg-red-50 border border-red-200 text-red-800 rounded-lg">
                    <ul class="list-disc pl-5 space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('customers.update', $customer) }}" class="bg-white rounded-xl border border-slate-200 p-6 space-y-6">
                @csrf
                @method('PUT')

                <div>
                    <label for="name" class="block text-sm font-medium text-slate-700 mb-1">Tên <span class="text-red-500">*</span></label>
                    <input type="text"
                           id="name"
                           name="name"
                           value="{{ old('name', $customer->name) }}"
                           required
                           class="w-full px-4 py-2.5 rounded-lg border border-slate-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 outline-none">
                </div>

                <div>
                    <label for="phone" class="block text-sm font-medium text-slate-700 mb-1">Số điện thoại <span class="text-red-500">*</span></label>
                    <input type="tel"
                           id="phone"
                           name="phone"
                           value="{{ old('phone', $customer->phone) }}"
                           required
                           class="w-full px-4 py-2.5 rounded-lg border border-slate-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 outline-none"
                           placeholder="09xxxxxxxx">
                </div>

                <div>
                    <label for="email" class="block text-sm font-medium text-slate-700 mb-1">Email</label>
                    <input type="email"
                           id="email"
                           name="email"
                           value="{{ old('email', $customer->email) }}"
                           class="w-full px-4 py-2.5 rounded-lg border border-slate-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 outline-none"
                           placeholder="khachhang@email.com">
                </div>

                <div>
                    <label for="address" class="block text-sm font-medium text-slate-700 mb-1">Địa chỉ</label>
                    <textarea id="address"
                              name="address"
                              rows="3"
                              class="w-full px-4 py-2.5 rounded-lg border border-slate-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 outline-none"
                              placeholder="Địa chỉ chi tiết">{{ old('address', $customer->address) }}</textarea>
                </div>

                <div>
                    <label for="notes" class="block text-sm font-medium text-slate-700 mb-1">Ghi chú</label>
                    <textarea id="notes"
                              name="notes"
                              rows="3"
                              class="w-full px-4 py-2.5 rounded-lg border border-slate-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 outline-none"
                              placeholder="Ghi chú thêm">{{ old('notes', $customer->notes) }}</textarea>
                </div>

                <div class="flex justify-end space-x-4 pt-4 border-t border-slate-200">
                    <a href="{{ route('customers.index') }}"
                       class="px-4 py-2.5 text-slate-600 hover:text-slate-900">Hủy</a>
                    <button type="submit"
                            class="px-4 py-2.5 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                        Cập nhật
                    </button>
                </div>
            </form>
        </main>
    </div>
</body>
</html>