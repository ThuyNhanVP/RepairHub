
## Local Development with Docker

Run the application stack with Docker Compose:

```bash
docker compose up -d --build
```

The stack includes:

- Laravel app (`app`)
- Nginx (`nginx`)
- MySQL (`mysql`)
- Redis (`redis`)
- Mailpit (`mailpit`)

Useful URLs:

- Application: http://localhost:8000
- Mailpit: http://localhost:8025

After the containers are up, run:

```bash
docker compose exec app composer install
docker compose exec app php artisan key:generate
docker compose exec app php artisan migrate
```
```bash
composer require laravel/boost --dev

php artisan boost:install
```
