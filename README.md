# RepairHub

RepairHub là hệ thống quản lý trung tâm sửa chữa thiết bị điện tử. Ứng dụng quản lý quy trình từ lúc tiếp nhận thiết bị, phân công sửa chữa, quản lý linh kiện đến bảo hành và yêu cầu bảo hành.

## Tính năng

- Đăng nhập và đăng xuất nhân viên.
- Quản lý khách hàng.
- Quản lý thiết bị của khách hàng.
- Tiếp nhận thiết bị sửa chữa.
- Theo dõi công việc sửa chữa và nhật ký sửa chữa.
- Quản lý linh kiện:
  - CRUD linh kiện.
  - Phân loại linh kiện.
  - Nhập, xuất và điều chỉnh tồn kho.
  - Cảnh báo linh kiện sắp hết.
- Quản lý phiếu bảo hành.
- Tiếp nhận và xử lý yêu cầu bảo hành.
- Dashboard thống kê:
  - Tổng số phiếu tiếp nhận.
  - Công việc đang sửa chữa.
  - Doanh thu tháng.
  - Phiếu bảo hành còn hạn.
  - Yêu cầu bảo hành đang xử lý.
  - Linh kiện sắp hết.
- Database notification khi trạng thái công việc sửa chữa thay đổi.

## Công nghệ sử dụng

| Thành phần | Công nghệ |
| --- | --- |
| Backend | Laravel 13, PHP 8.3+ |
| Frontend | Blade, Tailwind CSS 4, Vite |
| Database | MySQL 8.4 hoặc SQLite dùng cho test |
| Cache/Session/Queue | Database mặc định, Redis trong Docker |
| Email local | Mailpit |
| Kiểm thử | PHPUnit 12 |
| Code style | Laravel Pint |
| Container | Docker Compose |

## Yêu cầu môi trường

### Chạy local trên Windows/Laragon

- PHP 8.3 trở lên.
- Composer 2.x.
- Node.js và npm.
- MySQL nếu không sử dụng SQLite.

### Chạy bằng Docker

- Docker Desktop.
- Docker Compose plugin.

## Cài đặt local

Clone repository và đi vào thư mục dự án:

```powershell
git clone <repository-url>
cd TotNghiep
```

Cài dependency và khởi tạo ứng dụng:

```powershell
composer install
Copy-Item .env.example .env
php artisan key:generate
```

Trong `.env`, cấu hình database phù hợp với môi trường local. Ví dụ khi MySQL chạy trực tiếp trên máy:

```dotenv
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=totnghiep
DB_USERNAME=root
DB_PASSWORD=
```

Chạy migration và seed dữ liệu nền:

```powershell
php artisan migrate --seed
```

Cài và build frontend:

```powershell
npm install
npm run build
```

Khởi động server local:

```powershell
php artisan serve
```

Ứng dụng mặc định chạy tại <http://127.0.0.1:8000>.

Trong quá trình phát triển frontend, có thể dùng Vite ở chế độ watch:

```powershell
npm run dev
```

## Cài đặt bằng Docker

Docker Compose đã cấu hình các service:

| Service | Container | Cổng host | Mục đích |
| --- | --- | --- | --- |
| `app` | `repairhub-app` | nội bộ | PHP-FPM và Laravel |
| `nginx` | `repairhub-nginx` | `8000` | Web server |
| `mysql` | `repairhub-mysql` | `3307` | MySQL 8.4 |
| `redis` | `repairhub-redis` | `6379` | Cache/queue |
| `mailpit` | `repairhub-mailpit` | `8025`, `1025` | Email local |

Khởi động toàn bộ stack:

```powershell
docker compose up -d --build
```

Service `app` tự chạy `key:generate` và `migrate` khi khởi động. Nếu cần chạy thủ công:

```powershell
docker compose exec app php artisan migrate --seed
docker compose exec app npm install
docker compose exec app npm run build
```

Các địa chỉ thường dùng:

- Ứng dụng: <http://localhost:8000>
- Mailpit: <http://localhost:8025>
- MySQL từ máy host: `127.0.0.1:3307`
- Redis từ máy host: `127.0.0.1:6379`

Xem log hoặc dừng stack:

```powershell
docker compose logs -f app
docker compose down
```

## Tài khoản mặc định

Khi chạy seeder, tài khoản quản trị được tạo:

```text
Email: admin@repairhub.test
Mật khẩu: password123
```

Không sử dụng mật khẩu này trong môi trường production.

## Kiểm thử và kiểm tra code

Chạy toàn bộ test:

```powershell
php artisan test --compact
```

Hoặc dùng script Composer:

```powershell
composer test
```

Format các file PHP đã thay đổi:

```powershell
vendor\bin\pint --dirty --format agent
```

Kiểm tra whitespace trong diff:

```powershell
git diff --check
```

Build frontend production:

```powershell
npm run build
```

Test sử dụng SQLite in-memory theo cấu hình trong `phpunit.xml`, nên không cần database MySQL để chạy test.

## Cấu trúc chính

```text
app/
├── Http/Controllers/
│   ├── Auth/
│   ├── CustomerController.php
│   ├── DashboardController.php
│   ├── PartController.php
│   ├── ReceptionController.php
│   ├── RepairJobController.php
│   ├── WarrantyController.php
│   └── WarrantyClaimController.php
├── Models/
├── Notifications/
└── Providers/

database/
├── factories/
├── migrations/
└── seeders/

resources/
├── css/
├── js/
└── views/

routes/
└── web.php

tests/
├── Feature/
└── Unit/
```

## Quy trình nghiệp vụ chính

```text
Khách hàng
    ↓
Tiếp nhận thiết bị
    ↓
Tạo công việc sửa chữa
    ↓
Chẩn đoán / báo giá / sửa chữa
    ↓
Hoàn tất sửa chữa
    ↓
Tạo phiếu bảo hành
    ↓
Tiếp nhận yêu cầu bảo hành nếu phát sinh
```

Khi trạng thái `RepairJob` thay đổi, hệ thống tạo database notification cho kỹ thuật viên và nhân viên phụ trách phiếu tiếp nhận.

## Phát triển theo Git

Tạo branch từ `develop` trước khi bắt đầu feature mới:

```powershell
git checkout develop
git pull origin develop
git checkout -b feature/ten-feature
```

Trước khi commit:

```powershell
php artisan test --compact
vendor\bin\pint --dirty --format agent
git diff --check
```

Commit và push:

```powershell
git add .
git commit -m "feat: mô tả thay đổi" -m "Co-authored-by: Copilot <223556219+Copilot@users.noreply.github.com>"
git push -u origin feature/ten-feature
```

Pull Request nên target vào branch `develop`.

## Biến môi trường quan trọng

Không commit file `.env` hoặc thông tin bí mật. Sử dụng `.env.example` làm mẫu và cấu hình riêng cho từng môi trường.

Một số biến chính:

```dotenv
APP_URL=http://localhost:8000
DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=repairhub
DB_USERNAME=repairhub
DB_PASSWORD=secret
QUEUE_CONNECTION=database
CACHE_STORE=database
MAIL_MAILER=smtp
MAIL_HOST=mailpit
MAIL_PORT=1025
```

Khi chạy local ngoài Docker, thay `DB_HOST=mysql` bằng `127.0.0.1` và dùng cổng MySQL local tương ứng.
