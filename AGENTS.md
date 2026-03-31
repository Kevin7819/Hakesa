n# AGENTS.md - Developer Guidelines for Hakesa

This file provides guidelines for AI agents working on this Laravel + React project.

## Project Overview

- **Backend**: Laravel 13 (PHP 8.3+)
- **Frontend**: Vite + Alpine.js + Tailwind CSS
- **Testing**: Pest PHP testing framework
- **Database**: SQLite (default), supports MySQL/PostgreSQL
- **Auth**: Laravel Breeze

## Build/Lint/Test Commands

```bash
# Run all tests
composer test

# Run single test (filter by name)
php artisan test --filter=ExampleTest
vendor/bin/pest --filter=ExampleTest

# Run specific test file
vendor/bin/pest tests/Feature/ExampleTest.php

# Run tests with coverage
vendor/bin/pest --coverage

# Format code (Laravel Pint)
vendor/bin/pint
vendor/bin/pint --test  # dry-run

# Clear cache
php artisan config:clear && php artisan cache:clear && php artisan route:clear

# Frontend
npm install && npm run dev    # Install deps + dev server
npm run build                 # Production build

# Full stack
composer setup    # Initial setup (creates .env, migrations, assets)
composer dev      # Full dev environment (PHP server + queue + logs + Vite)
```

## Code Style Guidelines

### General

- Follow `.editorconfig` (4-space indentation, UTF-8, LF line endings)
- Trim trailing whitespace (except in .md files)

### PHP/Laravel

- **Formatting**: Laravel Pint (PSR-12 compatible)
- **Imports**: Use fully-qualified class names, group Laravel facades first
- **Types**: Use PHP 8.3 typed properties and return types
- **Naming**: Classes: `StudlyCaps`, Methods/Properties: `camelCase`, Constants: `UPPER_SNAKE_CASE`
- **Controllers**: Follow RESTful conventions, use dependency injection
- **Models**: Use Eloquent ORM, define relationships explicitly

Example:
```php
<?php
namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

final class UserController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json(User::select(['id', 'name', 'email'])->get());
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:users'],
            'password' => ['required', 'string', 'min:8'],
        ]);
        return response()->json(User::create($validated), 201);
    }
}
```

### Blade Templates

- Use `.blade.php` extension with `{{ $variable }}` for safe output
- Use `{!! $variable !!}` only when content is trusted

### JavaScript/TypeScript

- Use ES modules (`type="module"`), `const` over `var`, arrow functions
- Use `axios` for HTTP requests

### CSS/Tailwind

- Use Tailwind utility classes, Alpine.js for interactivity
- Responsive design: use `sm:`, `md:`, `lg:`, `xl:` prefixes

## Testing Guidelines (Pest PHP)

- Place unit tests in `tests/Unit/`, feature tests in `tests/Feature/`
- Use descriptive test names: `it_can_create_user`, `user_can_login`
- Use `uses(RefreshDatabase::class)` trait for database tests

Example:
```php
<?php
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('can create a user', function () {
    $response = $this->post('/register', [
        'name' => 'Test User',
        'email' => 'test@example.com',
        'password' => 'password123',
        'password_confirmation' => 'password123',
    ]);
    $response->assertRedirect('/dashboard');
    $this->assertDatabaseHas('users', ['email' => 'test@example.com']);
});
```

## Database

- Use migrations for schema changes, seeders for test data, factories for creation
- Use Eloquent relationships (hasOne, hasMany, belongsTo, etc.)

## Common Patterns

### Authorization & Permissions (spatie/laravel-permission)

- Use Laravel Policies for resource authorization
- Use `@can` directive in Blade for UI checks
- Define permissions in code, assign to roles
- Use middleware: `can:viewAny,App\Models\User`

### API Responses

```php
return response()->json(['data' => $resource], 200);     // Success
return response()->json(['data' => $resource], 201);     // Created
return response()->json(['errors' => $validator->errors()], 422); // Validation error
return response()->json(['message' => 'Not found'], 404); // Not found
```

## Important Files

- `routes/web.php`, `routes/api.php` - Routes
- `app/Models/`, `app/Http/Controllers/` - Models and Controllers
- `database/migrations/`, `resources/views/`, `tests/`

## Environment Setup

```bash
cp .env.example .env
php artisan key:generate
php artisan migrate
```

---

# Code Review Rules (GGA)

## Response Format
FIRST LINE must be exactly:
```
STATUS: PASSED
```
or
```
STATUS: FAILED
```

If FAILED, list violations as: `file:line - rule violated - issue`

## PHP/Laravel — REJECT if:
- Hardcoded secrets, API keys, passwords, or DB credentials in code
- `dd()` or `dump()` left in production code
- Missing return type declarations on public methods
- Raw SQL queries without parameter binding (SQL injection risk)
- `$request->all()` without validation — always use `$request->validate()` or Form Requests
- N+1 queries (looping relationships without eager loading)
- Missing `$fillable` or `$guarded` on Eloquent models
- Controllers with business logic — extract to Services/Actions
- Routes defined outside `routes/web.php` or `routes/api.php`
- Using `DB::raw()` without escaping
- Missing authorization checks on sensitive endpoints

## Blade Templates — REJECT if:
- `{!! $variable !!}` with user-generated content (XSS risk)
- Business logic in Blade — extract to Components or Controllers
- Inline styles (`style="..."`) — use Tailwind utilities
- Missing `@csrf` on forms
- Missing `@method('DELETE')` on delete forms

## JavaScript/Alpine.js — REJECT if:
- `var` usage — use `const`/`let`
- `console.log()` in production code
- Inline event handlers (`onclick="..."`) — use Alpine.js directives
- Missing error handling on fetch/axios calls

## CSS/Tailwind — REJECT if:
- Custom CSS when Tailwind utility exists
- Missing responsive prefixes for mobile-first design
- Hardcoded colors — use Tailwind config theme

## Security — ALWAYS REJECT if:
- Secrets in code or config files
- SQL injection vectors
- XSS vulnerabilities in Blade
- Missing CSRF protection on forms
- Missing authentication/authorization on routes
