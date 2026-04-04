<p align="center">
  <a href="https://github.com/Kevin7819/Hakesa" target="_blank">
    <img src="https://via.placeholder.com/400x120/FF6B8A/FFFFFF?text=HAKESA" alt="Hakesa Logo" width="400">
  </a>
</p>

<p align="center">
  <a href="https://github.com/Kevin7819/Hakesa/actions/workflows/tests.yml">
    <img src="https://github.com/Kevin7819/Hakesa/actions/workflows/tests.yml/badge.svg" alt="CI Status">
  </a>
  <a href="https://laravel.com">
    <img src="https://img.shields.io/badge/Laravel-13-FF2D20?logo=laravel&logoColor=white" alt="Laravel 13">
  </a>
  <a href="https://php.net">
    <img src="https://img.shields.io/badge/PHP-8.3+-777BB4?logo=php&logoColor=white" alt="PHP 8.3+">
  </a>
  <a href="https://pestphp.com">
    <img src="https://img.shields.io/badge/Tested%20with-Pest-FF2D20?logo=pestphp&logoColor=white" alt="Tested with Pest">
  </a>
  <a href="https://opensource.org/licenses/MIT">
    <img src="https://img.shields.io/badge/License-MIT-blue.svg" alt="License">
  </a>
</p>

<p align="center">
  <strong>E-commerce platform for personalized products and merchandising</strong>
</p>

---

## 📖 About

**Hakesa** is a full-featured e-commerce platform built for selling **customized products and merchandising**. It enables customers to browse a product catalog, personalize items (colors, sizes, engravings, custom text), add them to a cart, and place orders — all with a clean, responsive interface.

Administrators get a dedicated panel to manage the catalog, categories, orders, and moderate customer comments, with role-based access control powered by Spatie Laravel Permission.

### 🎯 Use Cases

- Corporate merchandise stores
- Custom gift businesses
- Brands selling configurable products
- Print-on-demand and personalization shops

---

## ✨ Features

### Customer-Facing

| Feature | Description |
|---------|-------------|
| **Product Catalog** | Browse, search, and filter products by category and price range |
| **Product Customization** | Select options like color, size, and custom text per product |
| **Shopping Cart** | Full cart management with quantity updates and item removal |
| **Checkout** | Simplified checkout flow with customer delivery details |
| **Order History** | View past orders with status tracking (`pending` → `processing` → `completed` / `cancelled`) |
| **Comments** | Leave comments on products (subject to admin moderation) |
| **User Profile** | Manage personal information and account settings |
| **Authentication** | Registration, login, and password reset via Laravel Breeze |
| **OTP Password Reset** | One-time password verification for secure password recovery |
| **Welcome Emails** | Automated welcome email on registration |

### Admin Panel

| Feature | Description |
|---------|-------------|
| **Dashboard** | Overview statistics for orders and key metrics |
| **Product Management** | Full CRUD for catalog products |
| **Category Management** | Organize products with categories |
| **Order Management** | View order details, update order statuses |
| **Comment Moderation** | Approve, reject, or delete customer comments |
| **Separate Auth Guard** | Independent admin authentication via `AdminUser` model |
| **Role-Based Access** | Fine-grained permissions via Spatie Laravel Permission |

---

## 🛠️ Tech Stack

### Backend

| Technology | Version | Purpose |
|------------|---------|---------|
| **Laravel** | 13.x | Application framework |
| **PHP** | 8.3+ | Runtime |
| **Laravel Breeze** | 2.4 | User authentication scaffolding |
| **Spatie Laravel Permission** | — | Roles & permissions |
| **Eloquent ORM** | — | Database abstraction & relationships |

### Frontend

| Technology | Version | Purpose |
|------------|---------|---------|
| **Blade** | — | Server-side templating |
| **Tailwind CSS** | 3.1+ | Utility-first CSS framework |
| **Alpine.js** | 3.4+ | Lightweight reactive components |
| **Vite** | 8.x | Frontend build tool |
| **Axios** | 1.11+ | HTTP client |

### Development & Testing

| Technology | Version | Purpose |
|------------|---------|---------|
| **Pest PHP** | 4.4+ | Testing framework |
| **Laravel Pint** | 1.27+ | Code style fixer |
| **Faker** | 1.23+ | Test data generation |
| **Concurrently** | 9.0+ | Parallel dev server orchestration |

---

## 📋 Requirements

| Dependency | Minimum Version |
|------------|-----------------|
| **PHP** | 8.3 |
| **Composer** | 2.2+ |
| **Node.js** | 18+ |
| **npm** | 9+ |
| **SQLite** | 3.x (development) |
| **MySQL / PostgreSQL** | — (production, optional) |

### PHP Extensions

Ensure the following extensions are enabled:

- `mbstring`
- `xml`
- `ctype`
- `json`
- `pdo_sqlite` (or `pdo_mysql` / `pdo_pgsql` for production)
- `fileinfo`
- `tokenizer`
- `bcmath`

---

## 🚀 Installation

### Quick Start

```bash
# Clone the repository
git clone https://github.com/Kevin7819/Hakesa.git
cd Hakesa

# One-command setup (installs deps, generates key, migrates, builds assets)
composer setup
```

### Manual Setup

```bash
# 1. Install PHP dependencies
composer install

# 2. Install frontend dependencies
npm install

# 3. Create environment file
cp .env.example .env

# 4. Generate application key
php artisan key:generate

# 5. Create SQLite database file (if using SQLite)
touch database/database.sqlite

# 6. Run migrations and seeders
php artisan migrate --seed

# 7. Build frontend assets
npm run build
```

### Default Test Users

After running seeders, the following accounts are available:

| Role | Email | Password |
|------|-------|----------|
| **Admin** | `admin@hakesa.com` | `admin123` |
| **Customer** | `cliente@test.com` | `password` |

> **Note:** Admin credentials can be customized via `ADMIN_EMAIL` and `ADMIN_PASSWORD` in `.env` before running seeders.

---

## ⚙️ Environment Configuration

Key `.env` variables to configure:

### Application

| Variable | Default | Description |
|----------|---------|-------------|
| `APP_NAME` | `Hakesa` | Application display name |
| `APP_ENV` | `local` | Environment (`local`, `production`) |
| `APP_DEBUG` | `true` | Enable debug mode (set `false` in production) |
| `APP_URL` | `http://localhost` | Base URL |
| `APP_LOCALE` | `es` | Application language |
| `BCRYPT_ROUNDS` | `12` | Password hashing cost |

### Database

| Variable | Default | Description |
|----------|---------|-------------|
| `DB_CONNECTION` | `sqlite` | Database driver (`sqlite`, `mysql`, `pgsql`) |
| `DB_HOST` | — | Database host (MySQL/PostgreSQL) |
| `DB_PORT` | — | Database port |
| `DB_DATABASE` | — | Database name or SQLite file path |
| `DB_USERNAME` | — | Database username |
| `DB_PASSWORD` | — | Database password |

### Mail

| Variable | Default | Description |
|----------|---------|-------------|
| `MAIL_MAILER` | `log` | Mail driver (`smtp`, `log`, `sendmail`, `mailgun`) |
| `MAIL_HOST` | `127.0.0.1` | SMTP server host |
| `MAIL_PORT` | `2525` | SMTP server port |
| `MAIL_USERNAME` | — | SMTP username |
| `MAIL_PASSWORD` | — | SMTP password |
| `MAIL_FROM_ADDRESS` | `hello@example.com` | Sender email address |
| `MAIL_FROM_NAME` | `${APP_NAME}` | Sender display name |

> For development, consider using [Mailpit](https://github.com/axllent/mailpit) or [Mailhog](https://github.com/mailhog/MailHog) to catch outgoing emails locally.

### Session & Queue

| Variable | Default | Description |
|----------|---------|-------------|
| `SESSION_DRIVER` | `database` | Session storage driver |
| `QUEUE_CONNECTION` | `database` | Queue driver |
| `CACHE_STORE` | `database` | Cache driver |

---

## 💻 Development

### Running the Dev Server

```bash
# Full development stack (server, queue, logs, Vite)
composer dev
```

This runs four processes concurrently:
- **PHP Server** — Laravel development server
- **Queue Worker** — Processes queued jobs (emails, etc.)
- **Logs** — Real-time log tail via Laravel Pail
- **Vite** — Hot module replacement for frontend assets

### Individual Commands

```bash
# Start PHP server only
php artisan serve

# Start Vite dev server
npm run dev

# Build production assets
npm run build

# Start queue worker
php artisan queue:work
```

### Code Style

```bash
# Auto-fix code style
vendor/bin/pint

# Check style without fixing
vendor/bin/pint --test
```

### Cache Management

```bash
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear
```

---

## 🧪 Testing

Hakesa uses **Pest PHP** for expressive, readable tests.

```bash
# Run all tests
composer test

# Run all tests (direct)
vendor/bin/pest

# Run a specific test file
vendor/bin/pest tests/Feature/CartTest.php

# Filter tests by name
vendor/bin/pest --filter="can add"

# Run with coverage report
vendor/bin/pest --coverage
```

### Test Structure

| Directory | Purpose |
|-----------|---------|
| `tests/Feature/` | Integration tests (HTTP, database, auth) |
| `tests/Unit/` | Unit tests (isolated logic) |

Tests use `RefreshDatabase` trait for clean database state and leverage factories for test data generation.

---

## 📁 Project Structure

```
hakesa/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Admin/           # Admin panel controllers
│   │   │   │   ├── AuthController.php
│   │   │   │   ├── CategoryController.php
│   │   │   │   ├── CommentController.php
│   │   │   │   ├── DashboardController.php
│   │   │   │   ├── OrderController.php
│   │   │   │   └── ProductController.php
│   │   │   ├── Auth/            # Breeze auth controllers
│   │   │   ├── CartController.php
│   │   │   ├── CatalogController.php
│   │   │   ├── CheckoutController.php
│   │   │   ├── ClientOrderController.php
│   │   │   ├── CommentController.php
│   │   │   ├── LandingController.php
│   │   │   ├── ProfileController.php
│   │   │   └── SitemapController.php
│   │   └── Middleware/
│   │       └── AdminAuth.php    # Admin guard middleware
│   ├── Mail/
│   │   ├── OrderConfirmation.php    # Order confirmation emails
│   │   ├── OtpVerification.php      # OTP code emails
│   │   └── WelcomeEmail.php         # Welcome emails
│   ├── Models/
│   │   ├── AdminUser.php        # Admin authentication model
│   │   ├── Cart.php             # Shopping cart
│   │   ├── CartItem.php         # Cart line items
│   │   ├── Category.php         # Product categories
│   │   ├── Comment.php          # Product comments
│   │   ├── Order.php            # Customer orders
│   │   ├── OrderItem.php        # Order line items
│   │   ├── PasswordResetOtp.php # OTP password reset tokens
│   │   ├── Product.php          # Catalog products
│   │   └── User.php             # Customer user model
│   ├── Providers/
│   │   └── AppServiceProvider.php
│   ├── Services/
│   │   └── OtpService.php       # OTP generation & verification
│   └── View/Components/         # Reusable Blade components
├── config/                      # Laravel configuration files
├── database/
│   ├── factories/               # Model factories for testing
│   ├── migrations/              # Database schema migrations
│   └── seeders/                 # Database seeders
├── public/                      # Web root (index.php, assets)
├── resources/
│   ├── css/                     # Tailwind & custom CSS
│   ├── js/                      # Alpine.js & Vite entry points
│   └── views/                   # Blade templates
│       ├── admin/               # Admin panel views
│       ├── auth/                # Authentication views
│       ├── cart/                # Shopping cart views
│       ├── catalog/             # Catalog views
│       ├── checkout/            # Checkout views
│       ├── comments/            # Comment components
│       ├── components/          # Reusable Blade components
│       ├── layouts/             # Layout templates
│       ├── orders/              # Order views
│       └── profile/             # User profile views
├── routes/
│   ├── web.php                  # Main web routes
│   └── auth.php                 # Breeze authentication routes
├── storage/                     # Logs, cache, uploads
├── tests/
│   ├── Feature/                 # Feature/integration tests
│   ├── Unit/                    # Unit tests
│   └── Pest.php                 # Pest configuration
├── .env.example                 # Environment template
├── composer.json                # PHP dependencies & scripts
├── package.json                 # Node.js dependencies
├── tailwind.config.js           # Tailwind configuration
├── vite.config.js               # Vite configuration
└── phpunit.xml                  # Test configuration
```

---

## 🔒 Security

Hakesa implements multiple layers of security:

| Feature | Implementation |
|---------|---------------|
| **CSRF Protection** | Laravel's built-in CSRF middleware on all POST/PUT/DELETE routes |
| **XSS Prevention** | Blade's `{{ }}` auto-escaping; `{!! !!}` used only for trusted content |
| **Rate Limiting** | Throttled routes: checkout (3/min), admin login (5/min), comments (5/min), cart actions (30/min) |
| **Password Hashing** | Bcrypt with configurable rounds (default: 12) |
| **OTP Verification** | Time-based one-time passwords for password reset flow |
| **Separate Admin Guard** | Independent authentication guard for admin users via `AdminUser` model |
| **Role-Based Permissions** | Spatie Laravel Permission for fine-grained access control |
| **SQL Injection Prevention** | Eloquent ORM with parameterized queries throughout |
| **Session Security** | Database-backed sessions with encryption option |

---

## 🚀 Deployment

### Production Checklist

1. **Set environment:**
   ```env
   APP_ENV=production
   APP_DEBUG=false
   APP_URL=https://yourdomain.com
   ```

2. **Configure database:** Switch from SQLite to MySQL or PostgreSQL.

3. **Configure mail:** Set up a real SMTP provider (Mailgun, SendGrid, SES, etc.).

4. **Install dependencies:**
   ```bash
   composer install --optimize-autoloader --no-dev
   npm ci
   npm run build
   ```

5. **Run migrations:**
   ```bash
   php artisan migrate --force
   ```

6. **Cache configuration:**
   ```bash
   php artisan config:cache
   php artisan route:cache
   php artisan view:cache
   ```

7. **Set up queue worker:** Configure a supervisor process for `php artisan queue:work`.

8. **Set file permissions:** Ensure `storage/` and `bootstrap/cache/` are writable by the web server.

### CI/CD

Hakesa uses **GitHub Actions** for continuous integration:

| Workflow | Triggers |
|----------|----------|
| **tests.yml** | Push to `master`/`main`, PRs, daily schedule |
| **issues.yml** | Issue lifecycle events |
| **pull-requests.yml** | PR lifecycle events |
| **update-changelog.yml** | Release events |

The test workflow runs a PHP matrix (8.3, 8.4, 8.5) with linting, unit tests, feature tests, and frontend build verification.

---

## 🤝 Contributing

Contributions are welcome! Here's how to get started:

1. **Fork** the repository
2. **Create a feature branch** (`git checkout -b feat/your-feature`)
3. **Make your changes** — follow the existing code style
4. **Run tests** to ensure nothing is broken:
   ```bash
   composer test
   vendor/bin/pint
   ```
5. **Commit** using [Conventional Commits](https://www.conventionalcommits.org/):
   ```bash
   git commit -m "feat(cart): add bulk quantity update"
   ```
6. **Push** to your branch (`git push origin feat/your-feature`)
7. **Open a Pull Request** with a clear description of changes

### Commit Convention

| Type | Description |
|------|-------------|
| `feat` | New feature |
| `fix` | Bug fix |
| `docs` | Documentation changes |
| `style` | Code style changes (formatting, no logic) |
| `refactor` | Code refactoring (no feature change, no bug fix) |
| `test` | Adding or updating tests |
| `chore` | Maintenance tasks, dependencies |

---

## 📄 License

This project is open-sourced under the [MIT License](LICENSE).

---

## 👨‍💻 Author

**Kevin7819** — [GitHub](https://github.com/Kevin7819)

---

<p align="center">
  Made with ❤️ in Costa Rica 🇨🇷
</p>
