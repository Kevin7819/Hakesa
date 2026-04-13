# Gracia Creativa

[![CI Status](https://github.com/Kevin7819/Gracia-Creativa/actions/workflows/tests.yml/badge.svg)](https://github.com/Kevin7819/Gracia-Creativa/actions/workflows/tests.yml)
[![License: MIT](https://img.shields.io/badge/License-MIT-yellow.svg)](LICENSE)
[![Laravel](https://img.shields.io/badge/Laravel-13-FF2D20.svg)](https://laravel.com)
[![PHP](https://img.shields.io/badge/PHP-8.3+-777BB4.svg)](https://php.net)

> Full-featured e-commerce platform for customized products and merchandising. Built with Laravel, Blade, Alpine.js, and a mobile-first API.

## Features

- **Product catalog** with search, category filters, and AJAX-powered filtering
- **Shopping cart** with stock validation and real-time updates
- **Checkout flow** with order creation, stock management, and email confirmation
- **Customer accounts** with order history, wishlist, and profile management
- **Admin panel** with product/category/order/comment management
- **Mobile API** (`/api/v1`) with Sanctum token authentication
- **OTP password reset** for web and API users
- **Responsive design** with Tailwind CSS 3 and Alpine.js 3
- **Full test suite** with Pest PHP (216 tests)

## Tech Stack

| Layer | Technology |
|-------|-----------|
| Backend | Laravel 13 (PHP 8.3+) |
| Frontend | Blade + Alpine.js 3 + Tailwind CSS 3 |
| Build | Vite 8 |
| API | Laravel Sanctum (token-based auth) |
| Testing | Pest PHP 4.x |
| Database | SQLite (dev), MySQL/PostgreSQL (prod) |
| Auth | Laravel Breeze (web) + custom admin guard |
| Permissions | spatie/laravel-permission |

## Requirements

- **PHP** 8.3 or higher
- **Composer** 2.x
- **Node.js** 18+ and npm
- **SQLite**, MySQL, or PostgreSQL

## Installation

```bash
# 1. Clone the repository
git clone https://github.com/Kevin7819/Gracia-Creativa.git
cd Gracia-Creativa

# 2. Install PHP dependencies
composer install

# 3. Copy environment file and generate app key
cp .env.example .env
php artisan key:generate

# 4. Run database migrations
php artisan migrate

# 5. Seed default data (creates admin user)
php artisan db:seed

# 6. Install and build frontend assets
npm install
npm run build
```

### Default Admin Credentials

After seeding:

| Role | Email | Password |
|------|-------|----------|
| Admin | `admin@graciacreativa.com` | `admin123` |

> **Change the default password immediately in production.**

## Development

```bash
# Start development server (Laravel + Vite + queue)
composer dev

# Or run components separately:
php artisan serve          # PHP server
npm run dev                # Vite dev server
php artisan queue:work     # Queue worker
```

## Testing

```bash
# Run all tests
vendor/bin/pest

# Run a single test file
vendor/bin/pest tests/Feature/CheckoutTest.php

# Run tests matching a description
vendor/bin/pest --filter="checkout"

# Run with coverage
vendor/bin/pest --coverage

# Auto-fix code style before committing
vendor/bin/pint
```

## API Overview

All API routes are prefixed with `/api/v1`. Authentication uses Laravel Sanctum tokens.

### Public Routes

| Method | Endpoint | Description |
|--------|----------|-------------|
| `POST` | `/api/v1/auth/login` | Login (returns token) |
| `POST` | `/api/v1/auth/register` | Register new account |
| `POST` | `/api/v1/auth/otp/request` | Request OTP for password reset |
| `POST` | `/api/v1/auth/otp/verify` | Verify OTP code |
| `POST` | `/api/v1/auth/password/reset/confirm` | Reset password with OTP |
| `GET` | `/api/v1/products` | List products (paginated) |
| `GET` | `/api/v1/products/{id}` | Get single product |
| `GET` | `/api/v1/categories` | List categories |

### Protected Routes (require `Authorization: Bearer {token}`)

| Method | Endpoint | Description |
|--------|----------|-------------|
| `POST` | `/api/v1/auth/logout` | Logout (revoke token) |
| `GET` | `/api/v1/auth/me` | Get current user profile |
| `GET` | `/api/v1/cart` | Get cart |
| `POST` | `/api/v1/cart/add/{product}` | Add to cart |
| `PATCH` | `/api/v1/cart/{item}` | Update cart item |
| `DELETE` | `/api/v1/cart/{item}` | Remove from cart |
| `GET` | `/api/v1/checkout` | Get checkout summary |
| `POST` | `/api/v1/checkout` | Place order |
| `GET` | `/api/v1/orders` | List user orders |
| `GET` | `/api/v1/orders/{order}` | Get order details |
| `GET` | `/api/v1/wishlist` | Get wishlist |
| `POST` | `/api/v1/wishlist/{product}` | Add to wishlist |
| `DELETE` | `/api/v1/wishlist/{product}` | Remove from wishlist |

### Example: Login

```bash
curl -X POST http://localhost/api/v1/auth/login \
  -H "Content-Type: application/json" \
  -d '{"email":"user@test.com","password":"password","device_name":"MyDevice"}'
```

Response:
```json
{
  "message": "Inicio de sesión exitoso.",
  "user": { "id": 1, "name": "User", "email": "user@test.com" },
  "token": "1|abc123..."
}
```

## Environment Variables

Key variables from `.env.example`:

| Variable | Default | Description |
|----------|---------|-------------|
| `APP_NAME` | `Gracia Creativa` | Application display name |
| `APP_ENV` | `local` | Environment (local, testing, production) |
| `APP_DEBUG` | `true` | Enable debug mode |
| `APP_URL` | `http://localhost` | Application URL |
| `DB_CONNECTION` | `sqlite` | Database driver |
| `DB_DATABASE` | `storage/database/database.sqlite` | Database file path |
| `MAIL_MAILER` | `log` | Mail driver (log, smtp, sendmail) |
| `QUEUE_CONNECTION` | `sync` | Queue driver (sync, database, redis) |
| `CORS_ALLOWED_ORIGINS` | *(empty)* | Comma-separated list of allowed origins for API CORS |
| `ADMIN_PASSWORD` | `password` | Default admin password (used by seeder) |

## Project Structure

```
app/
├── Http/
│   ├── Controllers/        # Web + API controllers
│   ├── Controllers/Api/    # API-specific controllers
│   ├── Controllers/Admin/  # Admin panel controllers
│   ├── Middleware/         # Custom middleware
│   └── Requests/           # Form request validation
├── Mail/                   # Mailable classes
├── Models/                 # Eloquent models
└── Services/               # Business logic services
database/
├── migrations/             # Database migrations
└── seeders/                # Database seeders
resources/
├── views/                  # Blade templates
│   ├── emails/             # Email templates
│   └── admin/              # Admin panel views
├── js/app.js               # Alpine.js stores and components
└── css/app.css             # Tailwind CSS entry
routes/
├── web.php                 # Web routes
├── api.php                 # API routes
├── auth.php                # Auth routes (Breeze)
└── console.php             # Console commands
tests/
├── Feature/                # Feature tests
└── Unit/                   # Unit tests
```

## Security

- **CSRF protection** on all web routes
- **Sanctum tokens** for API authentication
- **Rate limiting** on login, registration, OTP, checkout, and cart
- **Stock validation** with database-level locking (`lockForUpdate()`)
- **Order ownership checks** — users can only view their own orders
- **SQL injection prevention** — parameterized queries with proper LIKE escaping
- **XSS prevention** — Blade auto-escapes output
- **Security headers** — CSP, HSTS, X-Frame-Options, X-Content-Type-Options

## CI/CD

GitHub Actions runs on every push and PR:

- PHP lint
- Laravel Pint (code style)
- Unit tests
- Feature tests
- Frontend build (npm)

See `.github/workflows/tests.yml`.

## License

The MIT License (MIT). See [LICENSE](LICENSE) for details.

Copyright (c) 2026 Gracia Creativa
