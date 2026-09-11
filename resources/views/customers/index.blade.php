<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Khách hàng - {{ config('app.name', 'RepairHub') }}</title>
    @if (file_exists(public_path('build/manifest.json')))
        @vite(['resources/css/app.css', 'resources/css/login.css'])
    @endif
</head>
<body class="bg-slate-50 min-h-screen">
    <div class="min-h-screen flex flex-col">
        <header class="bg-white border-b border-slate-200 px-6 py-4">
            <div class="max-w-7xl mx-auto flex items-center justify-between">
                <h1 class="text-2xl font-semibold text-slate-900">Quản lý khách hàng</h1>
                <a href="{{ route('customers.create') }}"
                   class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                    Thêm khách hàng
                </a>
            </div>
        </header>

        <main class="flex-1 max-w-7xl w-full mx-auto px-6 py-8">
            @if (session('success'))
                <div class="mb-6 p-4 bg-green-50 border border-green-200 text-green-800 rounded-lg">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white rounded-xl border border-slate-200 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-slate-200">
                        <thead class="bg-slate-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Tên</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Số điện thoại</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Email</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Địa chỉ</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-slate-500 uppercase tracking-wider">Thao tác</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-slate-200">
                            @forelse ($customers as $customer)
                                <tr class="hover:bg-slate-50">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-slate-900">
                                        {{ $customer->name }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-600">
                                        {{ $customer->phone }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-600">
                                        {{ $customer->email ?? '-' }}
                                    </td>
                                    <td class="px-6 py-4 text-sm text-slate-600 max-w-xs truncate">
                                        {{ $customer->address ?? '-' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <a href="{{ route('customers.show', $customer) }}"
                                           class="text-blue-600 hover:text-blue-900 mr-4">Xem</a>
                                        <a href="{{ route('customers.edit', $customer) }}"
                                           class="text-indigo-600 hover:text-indigo-900 mr-4">Sửa</a>
                                        <form action="{{ route('customers.destroy', $customer) }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                    onclick="return confirm('Bạn có chắc chắn muốn xóa khách hàng này?')"
                                                    class="text-red-600 hover:text-red-900">Xóa</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-12 text-center text-slate-500">
                                        Chưa có khách hàng nào. <a href="{{ route('customers.create') }}" class="text-blue-600 hover:underline">Tạo khách hàng đầu tiên</a>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{ $customers->links() }}
            </div>
        </main>
    </div>
</body>
</html>