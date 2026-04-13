# AGENTS.md — Developer Guidelines for Hakesa

> **Read this before making any changes.** This file is for AI agents and developers working on this codebase.

## Memoria Persistente — ENGRAM (OBLIGATORIO)

**Todos los agents deben revisar engram al inicio de cada sesión.**

Al comenzar cualquier trabajo, ANTES de hacer nada:
1. Llamar a `mem_context` para obtener contexto de sesiones recientes
2. Si el usuario menciona algo del proyecto, llamar a `mem_search` con keywords relevantes

**Regla**: "Si el usuario menciona algo que ya hicimos, buscá en engram primero."

### Cuándo guardar en engram (proactivamente):
- Decisiones de arquitectura o diseño
- Bugs encontrados y cómo se corrigieron
- Patrones establecidos (naming, estructura, convenciones)
- Descubrimientos no obvios del codebase
- Preferencias del usuario aprendidas

### Formato de guardado:
```php
title: "Short searchable title"
type: "decision|bugfix|pattern|discovery|preference"
content: "**What**: ... **Why**: ... **Where**: ... **Learned**: ..."
topic_key: "optional stable key for evolving topics"
```

### Cuándo buscar en engram:
- Al inicio de cada sesión: `mem_context` + `mem_search` con keywords del proyecto
- Cuando el usuario menciona algo del proyecto: "recordar", "qué hicimos", "cómo solucionamos"
- Antes de crear algo nuevo: verificar si ya existe en memoria
- Cuando hay tareas pendientes o en progreso

### Session close (OBLIGATORIO):
Antes de terminar, siempre llamar a `mem_session_summary` con:
- Goal: qué se trabajó
- Discoveries: hallazgos técnicos
- Accomplished: lo completado
- Next Steps: lo que falta para la próxima sesión

## Project Overview

- **Backend**: Laravel 13 (PHP 8.3+) with Eloquent ORM
- **Frontend**: Vite 8 + Alpine.js 3 + Tailwind CSS 3
- **Testing**: Pest PHP 4.x (NOT PHPUnit directly)
- **Database**: SQLite (default), supports MySQL/PostgreSQL
- **Auth**: Laravel Breeze (users) + custom guard (`admin`) with `AdminUser` model
- **Permissions**: spatie/laravel-permission with `HasRoles` trait
- **Domain**: E-commerce — products, categories, cart, orders, comments, admin panel

## Build/Lint/Test Commands

```bash
# Full setup (one-time)
composer setup          # install deps, .env, key, migrate, npm build

# Development (full stack)
composer dev            # PHP server + queue + logs + Vite dev server

# Tests — use Pest, NOT PHPUnit directly
composer test           # clears config cache, runs all tests
vendor/bin/pest                          # all tests
vendor/bin/pest --filter=CartTest        # single file by name
vendor/bin/pest --filter="can add"       # test matching description
vendor/bin/pest tests/Feature/CartTest.php        # specific file
vendor/bin/pest --coverage               # with coverage report

# Lint / Format
vendor/bin/pint         # auto-fix style
vendor/bin/pint --test  # dry-run (check only, no changes)

# Cache / Routes
php artisan config:clear && php artisan cache:clear && php artisan route:clear

# Frontend
npm run dev             # Vite dev server (or use `composer dev`)
npm run build           # production build
```

## Code Style

### General

- **Indentation**: 4 spaces (2 for YAML)
- **Line endings**: LF (`\n`)
- **Charset**: UTF-8
- **Trailing whitespace**: trim except in `.md` files
- **Final newline**: always insert one
- **Editor**: config in `.editorconfig`

### PHP / Laravel

- **Formatter**: Laravel Pint (PSR-12 compatible) — run `vendor/bin/pint` before committing
- **PHP version**: 8.3+ — use typed properties, return types, `readonly`, attributes
- **Imports**: fully-qualified class names, group Laravel facades first
- **Naming**:
  - Classes: `StudlyCaps` (e.g. `ProductController`)
  - Controllers: end in `Controller`
  - Methods/properties: `camelCase`
  - Constants: `UPPER_SNAKE_CASE`
- **Return types**: ALWAYS declare them on public methods (`View`, `JsonResponse`, `RedirectResponse`)
- **Null checks**: space after negation — `if (! $product->is_active)` not `if (!$product)`
- **Attributes**: use PHP 8.3 attributes instead of properties where possible:
  ```php
  #[Fillable(['name', 'email', 'password'])]
  #[Hidden(['password', 'remember_token'])]
  ```
- **Casts**: use the `casts()` method, not the `$casts` property:
  ```php
  protected function casts(): array
  {
      return ['price' => 'decimal:2', 'is_active' => 'boolean'];
  }
  ```

### Controllers

- **Validation**: inline with `$request->validate([...])` or Form Requests
- **Authorization**: handled via middleware (`auth`, `admin.auth`) in routes, NOT in controllers
- **Dual response**: use `$request->wantsJson()` to return JSON or redirect with flash messages
- **DB transactions**: wrap multi-step operations in `DB::transaction(fn () => ...)`
- **Business logic**: keep thin — extract complex logic to Services/Actions if it grows

### Models

- Use Eloquent ORM, define all relationships explicitly with typed returns:
  ```php
  public function category(): BelongsTo { ... }
  ```
- Define `$guarded` or `#[Fillable]` on every model
- Use scopes for reusable query constraints:
  ```php
  public function scopeActive(Builder $query): Builder { ... }
  ```

### Blade Templates

- `.blade.php` extension, use `{{ $variable }}` for safe output
- `{!! $variable !!}` ONLY for trusted content — never user-generated
- Use Tailwind utility classes, no inline styles
- Alpine.js for interactivity (`x-data`, `x-show`, `@click`)

### JavaScript / Alpine.js

- ES modules (`type="module"`), `const`/`let` never `var`
- `axios` for HTTP requests with error handling
- Alpine.js stores in `resources/js/app.js` (e.g. `$store.cart`)
- No `console.log()` in production code

### CSS / Tailwind

- Utility-first with Tailwind — no custom CSS when a utility exists
- Responsive: `sm:`, `md:`, `lg:`, `xl:` prefixes (mobile-first)
- Brand colors defined in `tailwind.config.js` as `hakesa.*` (e.g. `bg-hakesa-pink`)

### Routes

- Spanish URLs: `/carrito`, `/productos`, `/checkout`, `/mis-pedidos`, `/perfil`
- Named routes: `catalog.index`, `cart.index`, `admin.products.index` (prefix `admin.`)
- Rate limiting: `throttle:3,1` on checkout, `throttle:5,1` on admin login

### Messages

- All user-facing messages in **Spanish** (Costa Rica — `₡` for currency)
- Use flash messages: `session()->flash('success', 'Mensaje aquí')`

## Testing (Pest PHP)

- Place unit tests in `tests/Unit/`, feature tests in `tests/Feature/`
- Use `describe`/`it`/`beforeEach` — NOT PHPUnit's `test*` methods
- `uses(RefreshDatabase::class)` is set globally in `tests/Pest.php` for Feature tests
- Test names describe behavior: `it('can add a product to cart')`
- Use factories: `User::factory()->create()`, `Product::factory()->create()`
- Auth simulation: `$this->actingAs($user)`
- JSON APIs: `postJson`, `patchJson`, `deleteJson`
- Assertions: `assertRedirect`, `assertStatus`, `assertJsonFragment`, `assertSee`, `assertDatabaseHas`

```php
uses(RefreshDatabase::class);

describe('Cart', function () {
    beforeEach(function () {
        $this->user = User::factory()->create();
        $this->product = Product::factory()->create(['price' => 1000, 'stock' => 5]);
    });

    it('can add a product to cart', function () {
        $response = $this->actingAs($this->user)
            ->postJson('/carrito/agregar', ['product_id' => $this->product->id, 'quantity' => 1]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('cart_items', [
            'user_id' => $this->user->id,
            'product_id' => $this->product->id,
        ]);
    });
});
```

## Security Rules — ALWAYS REJECT

- Hardcoded secrets, API keys, passwords, or DB credentials in code
- `dd()` or `dump()` in production code
- Raw SQL without parameter binding (SQL injection)
- `$request->all()` without validation
- `{!! $variable !!}` with user-generated content (XSS)
- Missing `@csrf` on forms, missing `@method('DELETE')` on delete forms
- Missing auth/authorization on sensitive routes
- N+1 queries (looping relationships without eager loading)
- `DB::raw()` without escaping

## API Response Conventions

```php
return response()->json(['data' => $resource], 200);       // Success
return response()->json(['data' => $resource], 201);       // Created
return response()->json(['errors' => $validator->errors()], 422); // Validation
return response()->json(['message' => 'No encontrado'], 404);     // Not found
```

## Important Files

| Path | Purpose |
|------|---------|
| `routes/web.php` | Web routes |
| `routes/auth.php` | Auth routes (Breeze) |
| `app/Models/` | Eloquent models |
| `app/Http/Controllers/` | Controllers (public) |
| `app/Http/Controllers/Admin/` | Admin panel controllers |
| `app/Http/Middleware/` | Security & auth middleware |
| `database/migrations/` | Schema migrations |
| `resources/views/` | Blade templates |
| `resources/js/app.js` | Alpine.js stores & setup |
| `tests/` | Pest tests |
| `tailwind.config.js` | Tailwind + brand colors |
| `phpunit.xml` | Test config (SQLite in-memory) |

## CI / GitHub Actions

- Workflow: `.github/workflows/tests.yml`
- Runs on: push to `master`/`main`, PRs, daily cron
- PHP matrix: 8.3, 8.4, 8.5
- Steps: lint (Pint) → unit tests → feature tests → frontend build

## Automatic Workflows — DO WITHOUT ASKING

### Issue Creation (auto)

When the user asks for a task that involves **3+ files** or **new features/refactors** (not a quick fix):
1. Create a GitHub issue first using `issue-creation` skill
2. Use `feat(scope): description` for features, `fix(scope): description` for bugs
3. Labels: `enhancement` for features, `bug` for fixes
4. Then implement against the issue
5. DO NOT ask permission — just do it and mention the issue number

**Skip issue creation for**: one-liners, typo fixes, single file reads/questions.

### Branch + PR (auto)

When the user says "sube los cambios", "push", "hacé un PR", or similar:
1. Create branch: `feat/description` or `fix/description` (lowercase, no spaces)
2. Commit with conventional commits: `feat(scope): description`
3. Push and create PR using `branch-pr` skill
4. PR body: link issue with `Closes #N`, summary, changes table
5. DO NOT ask permission — just do it

**Conventional commit types**: `feat`, `fix`, `docs`, `refactor`, `chore`, `style`, `perf`, `test`

### Commit Rules (auto)

- Use conventional commits ALWAYS: `type(scope): description`
- No `Co-Authored-By` trailers ever
- Run `vendor/bin/pint` before committing (auto-fix style)
- Run `composer test` before pushing (verify nothing broke)
