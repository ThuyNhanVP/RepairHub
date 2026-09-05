<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dashboard - {{ config('app.name', 'RepairHub') }}</title>
</head>
<body class="min-h-screen bg-slate-100">
    <main class="mx-auto max-w-5xl px-4 py-10">
        <div class="rounded-2xl bg-white p-8 shadow">
            <h1 class="text-3xl font-semibold text-slate-900">Dashboard</h1>
            <p class="mt-2 text-slate-600">Bạn đã đăng nhập thành công vào RepairHub.</p>

            <form method="POST" action="{{ route('logout') }}" class="mt-6">
                @csrf
                <button type="submit" class="rounded-lg bg-red-600 px-4 py-2 font-medium text-white hover:bg-red-500">
                    Đăng xuất
                </button>
            </form>
        </div>
    </main>
</body>
</html>
