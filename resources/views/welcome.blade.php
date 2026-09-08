<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'RepairHub') }}</title>
    @vite(['resources/css/app.css'])
</head>
<body>
    <div class="welcome-container">
        <div class="welcome-card">
            <div class="welcome-logo">RH</div>
            <h1 class="welcome-title">{{ config('app.name', 'RepairHub') }}</h1>
            <p class="welcome-subtitle">Hệ thống quản lý tiếp nhận, sửa chữa và bảo hành thiết bị</p>

            @if (Route::has('login'))
                <div class="welcome-actions">
                    @auth
                        <a href="{{ url('/dashboard') }}" class="btn btn-primary">Vào Dashboard</a>
                    @else
                        <a href="{{ route('login') }}" class="btn btn-primary">Đăng nhập</a>
                    @endauth
                </div>
            @endif
        </div>
    </div>
</body>
</html>
