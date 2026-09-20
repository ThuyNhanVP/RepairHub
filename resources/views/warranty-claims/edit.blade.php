<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Cập nhật yêu cầu bảo hành</title>@if (file_exists(public_path('build/manifest.json')))
    @vite(['resources/css/app.css', 'resources/css/login.css', 'resources/css/customers.css']) @endif
</head>

<body class="customers-page">
    <div class="cust-layout">
        <header class="cust-header">
            <div class="cust-header-inner">
                <h1 class="cust-title">Cập nhật {{ $warrantyClaim->claim_code }}</h1><a
                    href="{{ route('warranty-claims.show', $warrantyClaim) }}" class="cust-btn cust-btn-secondary">Quay
                    lại</a>
            </div>
        </header>
        <main class="cust-main">
            <div class="cust-card">
                <div class="cust-card-body">
                    <form method="POST" action="{{ route('warranty-claims.update', $warrantyClaim) }}"
                        class="cust-form">@csrf @method('PUT')
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div><label class="cust-label">Mã yêu cầu</label><input name="claim_code"
                                    value="{{ old('claim_code', $warrantyClaim->claim_code) }}" class="cust-input"
                                    required></div>
                            <div><label class="cust-label">Trạng thái *</label><select name="status"
                                    class="cust-input">@foreach (['received' => 'Đã tiếp nhận', 'diagnosing' => 'Đang kiểm tra', 'approved' => 'Đã duyệt', 'rejected' => 'Từ chối', 'completed' => 'Hoàn tất', 'cancelled' => 'Đã hủy'] as $value => $label)
                                    <option value="{{ $value }}" @selected(old('status', $warrantyClaim->status) === $value)>{{ $label }}</option>@endforeach
                                </select></div>
                        </div>
                        <div><label class="cust-label">Mô tả lỗi *</label><textarea name="issue" rows="5"
                                class="cust-textarea" required>{{ old('issue', $warrantyClaim->issue) }}</textarea>
                        </div>
                        <div><label class="cust-label">Kết quả xử lý</label><textarea name="resolution" rows="5"
                                class="cust-textarea">{{ old('resolution', $warrantyClaim->resolution) }}</textarea>
                        </div>
                        <div><label class="cust-label">Ghi chú</label><textarea name="notes" rows="3"
                                class="cust-textarea">{{ old('notes', $warrantyClaim->notes) }}</textarea></div>
                        <div class="cust-actions"><button class="cust-btn cust-btn-primary">Lưu thay đổi</button></div>
                    </form>
                </div>
            </div>
        </main>
    </div>
</body>

</html>