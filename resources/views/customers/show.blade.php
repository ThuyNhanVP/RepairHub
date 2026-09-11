<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $customer->name }} - {{ config('app.name', 'RepairHub') }}</title>
    @if (file_exists(public_path('build/manifest.json')))
        @vite(['resources/css/app.css', 'resources/css/login.css'])
    @endif
</head>
<body class="bg-slate-50 min-h-screen">
    <div class="min-h-screen flex flex-col">
        <header class="bg-white border-b border-slate-200 px-6 py-4">
            <div class="max-w-2xl mx-auto flex items-center justify-between">
                <h1 class="text-2xl font-semibold text-slate-900">Chi tiết khách hàng</h1>
                <div class="flex items-center space-x-2">
                    <a href="{{ route('customers.edit', $customer) }}"
                       class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">Sửa</a>
                    <a href="{{ route('customers.index') }}"
                       class="px-4 py-2 text-slate-600 hover:text-slate-900">Quay lại</a>
                </div>
            </div>
        </header>

        <main class="flex-1 max-w-2xl w-full mx-auto px-6 py-8">
            <div class="bg-white rounded-xl border border-slate-200 p-6 space-y-6">
                <div>
                    <h2 class="text-lg font-semibold text-slate-900">{{ $customer->name }}</h2>
                </div>

                <dl class="grid grid-cols-1 gap-x-4 gap-y-6 sm:grid-cols-2">
                    <div>
                        <dt class="text-sm font-medium text-slate-500">Số điện thoại</dt>
                        <dd class="mt-1 text-sm text-slate-900">{{ $customer->phone }}</dd>
                    </div>

                    <div>
                        <dt class="text-sm font-medium text-slate-500">Email</dt>
                        <dd class="mt-1 text-sm text-slate-900">{{ $customer->email ?? '-' }}</dd>
                    </div>

                    <div>
                        <dt class="text-sm font-medium text-slate-500">Địa chỉ</dt>
                        <dd class="mt-1 text-sm text-slate-900">{{ $customer->address ?? '-' }}</dd>
                    </div>

                    <div>
                        <dt class="text-sm font-medium text-slate-500">Ghi chú</dt>
                        <dd class="mt-1 text-sm text-slate-900">{{ $customer->notes ?? '-' }}</dd>
                    </div>

                    <div>
                        <dt class="text-sm font-medium text-slate-500">Ngày tạo</dt>
                        <dd class="mt-1 text-sm text-slate-900">{{ $customer->created_at->format('d/m/Y H:i') }}</dd>
                    </div>

                    <div>
                        <dt class="text-sm font-medium text-slate-500">Cập nhật lần cuối</dt>
                        <dd class="mt-1 text-sm text-slate-900">{{ $customer->updated_at->format('d/m/Y H:i') }}</dd>
                    </div>
                </dl>

                <div class="flex justify-end space-x-2 pt-4 border-t border-slate-200">
                    <a href="{{ route('customers.edit', $customer) }}"
                       class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">Sửa</a>
                    <form action="{{ route('customers.destroy', $customer) }}" method="POST" class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                                onclick="return confirm('Bạn có chắc chắn muốn xóa khách hàng này?')"
                                class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors">
                            Xóa
                        </button>
                    </form>
                    <a href="{{ route('customers.index') }}"
                       class="px-4 py-2 text-slate-600 hover:text-slate-900">Quay lại</a>
                </div>
            </div>
        </main>
    </div>
</body>
</html>