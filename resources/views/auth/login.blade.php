<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Đăng nhập - {{ config('app.name', 'RepairHub') }}</title>
    @if (file_exists(public_path('build/manifest.json')))
        @vite(['resources/css/app.css', 'resources/css/login.css'])
    @endif
</head>

<body class="login-page">
    <div class="login-container">
        <div class="login-card">
            <div class="login-logo">RH</div>
            <h1 class="login-title">Đăng nhập</h1>
            <p class="login-subtitle">Vui lòng đăng nhập để quản lý hệ thống RepairHub.</p>

            <form method="POST" action="{{ route('login.store') }}" class="login-form">
                @csrf

                <div>
                    <label for="email" class="login-field">Email</label>
                    <input id="email" name="email" type="email" value="{{ old('email') }}" required autofocus
                        class="login-input">
                </div>

                <div>
                    <label for="password" class="login-field">Mật khẩu</label>
                    <input id="password" name="password" type="password" required class="login-input">
                </div>
                @if ($errors->any())
                    <div class="login-error">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <label class="login-checkbox-label">
                    <input type="checkbox" name="remember" value="1" class="login-checkbox">
                    Ghi nhớ đăng nhập
                </label>

                <button type="submit" class="login-btn">
                    Đăng nhập
                </button>
            </form>
        </div>
    </div>
</body>

</html>