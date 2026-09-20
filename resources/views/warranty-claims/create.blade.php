<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Tiếp nhận bảo hành</title>@if (file_exists(public_path('build/manifest.json')))
    @vite(['resources/css/app.css', 'resources/css/login.css', 'resources/css/customers.css']) @endif
</head>

<body class="customers-page">
    <div class="cust-layout">
        <header class="cust-header">
            <div class="cust-header-inner">
                <h1 class="cust-title">Tiếp nhận yêu cầu bảo hành</h1><a href="{{ route('warranty-claims.index') }}"
                    class="cust-btn cust-btn-secondary">Quay lại</a>
            </div>
        </header>
        <main class="cust-main">
            <div class="cust-card">
                <div class="cust-card-body">
                    @if ($errors->any())
                        <div class="cust-error">
                            <ul>@foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>@endforeach
                            </ul>
                    </div>@endif
                    <form method="POST" action="{{ route('warranty-claims.store') }}" class="cust-form">@csrf
                        <div><label class="cust-label">Phiếu bảo hành còn hiệu lực *</label><select name="warranty_id"
                                class="cust-input" required>
                                <option value="">-- Chọn phiếu bảo hành --</option>@foreach ($warranties as $warranty)
                                    <option value="{{ $warranty->id }}" @selected(old('warranty_id') == $warranty->id)>
                                        {{ $warranty->warranty_code }} - {{ $warranty->customer->name }} -
                                {{ $warranty->device->brand }} {{ $warranty->device->model }}</option>@endforeach
                            </select></div>
                        <div><label class="cust-label">Mã yêu cầu</label><input name="claim_code"
                                value="{{ old('claim_code') }}" class="cust-input" placeholder="Tự động nếu bỏ trống">
                        </div>
                        <div><label class="cust-label">Mô tả lỗi *</label><textarea name="issue" rows="5"
                                class="cust-textarea" required>{{ old('issue') }}</textarea></div>
                        <div><label class="cust-label">Ghi chú</label><textarea name="notes" rows="3"
                                class="cust-textarea">{{ old('notes') }}</textarea></div>
                        <div class="cust-actions"><a href="{{ route('warranty-claims.index') }}"
                                class="cust-btn cust-btn-secondary">Hủy</a><button
                                class="cust-btn cust-btn-primary">Tiếp nhận</button></div>
                    </form>
                </div>
            </div>
        </main>
    </div>
</body>

</html>