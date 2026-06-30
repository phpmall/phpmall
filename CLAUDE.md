# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview

PHPMall is a B2B2C multi-merchant e-commerce platform. It is a PHP monorepo with a DDD backend and five frontend clients.

- **Backend**: Laravel 13.8, PHP ^8.4, Octane (Swoole)
- **Frontend clients** (in `packages/`): admin, seller, supplier, user (Vue 3 + Vite), mobile (UniApp 3)
- **PC mall**: Laravel Blade + Vite + Tailwind CSS in `resources/views/`
- **Database**: MySQL 8.4, Redis, Elasticsearch 9.x
- **Package managers**: Composer (PHP), pnpm (JS monorepo)

## Common Commands

### Backend

```bash
# Development server
php artisan serve

# Run all tests
php artisan test
# or
composer test

# Run a single test class or method
./vendor/bin/phpunit --filter=OrderTest
./vendor/bin/phpunit --filter=OrderTest::test_it_creates_order

# Code style
./vendor/bin/pint --config=pint.json
composer lint          # fix
composer lint:check    # check only

# Static analysis
./vendor/bin/phpstan analyse --configuration=phpstan.neon --memory-limit=1G
composer analyse

# Run everything (lint + analyse + test)
composer check

# Full local dev stack (server + queue + logs + Vite)
composer dev

# Docker / Sail
./vendor/bin/sail up -d
./vendor/bin/sail artisan migrate:fresh --seed

# Database
php artisan migrate:fresh --seed
php artisan optimize:clear

# Routes
php artisan gen:route        # regenerate app/Api/*/Routes/route.gen.php
php artisan route:list

# Modules
php artisan make:module {Domain}
composer dump-autoload       # required after adding a module

# Queue and scheduling
php artisan queue:work
php artisan schedule:work
php artisan horizon

# Install git hooks
composer git-hooks
```

### Frontend

```bash
pnpm install

# Dev servers
pnpm --filter phpmall-user dev
pnpm --filter phpmall-admin dev
pnpm --filter phpmall-seller dev
pnpm --filter phpmall-supplier dev
pnpm --filter phpmall-mobile dev:h5
pnpm --filter phpmall-mobile dev:mp-weixin

# Build
pnpm --filter phpmall-admin build
pnpm --filter phpmall-mobile build:h5

# Quality
pnpm --filter phpmall-admin lint
pnpm --filter phpmall-admin format
pnpm --filter phpmall-admin test:unit
pnpm --filter phpmall-admin test:e2e
```

## High-Level Architecture

### Backend: DDD modules + API channels

The backend is split into two complementary layer directories:

1. **`app/Modules/{Domain}/`** — DDD domain modules (e.g. `Product`, `Order`, `Payment`).
   Each module owns its own persistence, entities, repositories, services and provider.
   Standard subdirectories:
   - `Database/{factories,migrations,seeders}`
   - `Entities/` — domain entities / aggregates (framework-free PHP)
   - `Models/` — Eloquent models
   - `Repositories/` — data access abstraction
   - `Services/` — application / domain services
   - `Http/{Controllers,Middleware,Requests,Responses}` — module-facing HTTP layer
   - `Providers/{Domain}ServiceProvider.php`
   - `Routes/web.php`

2. **`app/Api/{Channel}/`** — public API surface organized by client channel.
   Channels: `Admin`, `Common`, `Portal`, `Seller`, `Shop`, `Supplier`, `User`.
   Each channel contains:
   - `Controllers/` — thin controllers that call module services
   - `Requests/` — request DTO / form validation (`{Action}Request.php`)
   - `Responses/` — response DTO / resources (`{Action}Response.php`)
   - `Routes/route.php` — manual route group
   - `Routes/route.gen.php` — generated routes; **do not edit by hand**

### Route loading

`routes/api.php` discovers every `app/Api/*/Routes/route.php` and includes it.
Each `route.php` typically groups a prefix and requires its generated sibling:

```php
Route::prefix('user')->group(function () {
    require __DIR__.'/route.gen.php';
});
```

Regenerate the generated routes with `php artisan gen:route` after adding or renaming API controllers.

### Module registration

Every module must be registered in `bootstrap/providers.php` in the correct loading order:

1. Generic domains (`Auth`, `Notification`, `System`, `AuditLog`)
2. Core domains (`Merchant`, `Shop`, `Product`, `Inventory`, `Order`, `Payment`, `Marketing`, `Distribution`)
3. Compliance core domains
4. Supporting domains
5. Compliance supporting domains
6. Existing `User` module

After creating a new module, run `composer dump-autoload`.

### Cross-module communication

- Prefer Laravel events for decoupled module communication.
- Call other modules through their repositories or application services.
- **Do not** query or write another module's tables directly.

### Frontend monorepo

`packages/` contains the five clients. The Vue 3 apps share a common stack:

- Pinia for state management
- vue-router for routing
- Vite for building
- Vitest for unit tests, Playwright for E2E
- ESLint + Oxlint + Prettier

The mobile package is UniApp 3 and supports H5, WeChat Mini Program and App targets.

### Code-quality configuration

- `pint.json` — Laravel preset plus ordered imports, no unused imports, short array syntax, single quotes.
- `phpstan.neon` — Larastan, level 6, paths `app`, `routes`, `database`; excludes `app/Providers`, `app/Support`, `app/Modules/User`, `bootstrap`.
- `phpunit.xml` — SQLite in-memory for testing, `APP_ENV=testing`.

## Project Conventions

- All PHP files start with `declare(strict_types=1);`.
- All methods must declare parameter and return types.
- Class names are `PascalCase`; methods / properties are `camelCase`; table names are `snake_case` plural.
- Use `int` (cents) for monetary amounts; never `float`.
- Payment callbacks must verify signatures and be idempotent.
- Sensitive data (ID cards, bank cards) is encrypted with AES-256-GCM at rest.
- Soft deletes use `deleted_at`; timestamps use `created_at` / `updated_at`.
- Index naming: `idx_{table}_{column}`; unique index naming: `udx_{table}_{column}`.

## Important Files

| File | Purpose |
|------|---------|
| `bootstrap/providers.php` | Module provider registration order |
| `routes/api.php` | Auto-loads `app/Api/*/Routes/route.php` |
| `composer.json` | PHP dependencies and composer scripts |
| `phpstan.neon` | PHPStan / Larastan configuration |
| `pint.json` | Laravel Pint code-style configuration |
| `vite.config.js` | Vite build config for the PC mall Blade entrypoints |
| `app/Modules/README.md` | Full module list and DDD directory spec |
| `AGENTS.md` | Project-wide agent instructions, BRD/PRD/tech docs index |
