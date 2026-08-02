# EnzoBank (XMIN) — Agent Guide

> ## 🚨 READ THIS FIRST — LIVE SITE SAFETY PROTOCOL (MANDATORY)
>
> **This site is LIVE (`enzobank.org`) with real users and real money flows.**
> Before making ANY change, and after EVERY change, you MUST follow the
> mandatory safety protocol in **`LIVE_SITE_SAFETY.md`** (same folder as this file).
>
> Summary of the rules:
> 1. **Verify after EVERY change** — Blade compile → phpunit (host + container) →
>    reload container → HTTP smoke test → log must stay clean → only then commit & push.
> 2. **NEVER** run `migrate:fresh`, `db:wipe`, table truncates, or `route:cache` on live.
> 3. **Tests hit the real production DB** — treat every query as destructive.
> 4. **Never edit a file you haven't read**; never leave scratch scripts behind.
> 5. If something breaks: revert immediately (`git checkout`/`git revert`), reload,
>    re-verify, and report.
>
> Full checklist, forbidden operations, rollback steps, and live gotchas are in
> `LIVE_SITE_SAFETY.md`. Read it before your first edit.

## Project Identity

- **App name**: XMIN (branded as EnzoBank / iBanking)
- **Source location**: `/var/www/enzobank`
- **Stack**: Laravel 9.x, PHP 8.1+, PostgreSQL (Supabase), Bootstrap 5, Sass, jQuery
- **Domain**: `enzobank.org` (production)
- **Infra**: Docker PHP-FPM container, deployed to cPanel + Cloudflare Pages (Wrangler)
- **Docker config**: `/home/ubuntu/docker/` — PHP 8.2-FPM, pdo_pgsql/pgsql, volume mount
- **Git**: 16 commits, single `main` branch, no PR workflow

---

## Essential Commands

```bash
# PHP dependencies
composer install

# Frontend assets (Vite)
npm install && npm run build          # Production build
npm run dev                           # Dev mode with HMR

# Laravel
php artisan storage:link              # Storage symlink
php artisan migrate:fresh --seed      # Fresh migration + seed
php artisan passport:install          # API OAuth keys
php artisan optimize:clear            # Clear all caches (dev)
php artisan config:cache              # Production config cache
php artisan route:cache               # ⚠️ Fails unless routes/web.php closure removed
php artisan view:cache                # Production view cache
php artisan deploy:backup             # Pre-deployment DB dump
php artisan deploy:backup --rollback  # Rollback from latest backup
php artisan deploy:optimize           # Post-deployment optimizations

# Testing
php artisan test
./vendor/bin/phpunit
./vendor/bin/phpunit tests/Unit/LoanCalculatorTest
./vendor/bin/pint                     # Laravel Pint (PSR-12)

# Dev server
php artisan serve
```

---

## Code Organization

```
/var/www/enzobank/
├── app/
│   ├── Console/Commands/         # artisan commands: deploy:backup, deploy:optimize
│   ├── Constants/                # GlobalConst, PaymentGatewayConst, AdminRoleConst, etc.
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Admin/            # Admin panel (Currency, BankList, UserCare, etc.)
│   │   │   ├── Api/V1/User/      # Mobile API (Passport auth)
│   │   │   ├── Frontend/         # Public website (IndexController)
│   │   │   └── User/             # Authenticated user web (Dashboard, Loans, etc.)
│   │   ├── Helpers/
│   │   │   ├── helpers.php       # ~1100+ lines autoloaded via composer.json
│   │   │   ├── PaymentGateway.php # Gateway orchestrator
│   │   │   ├── Response.php      # JSON response
│   │   │   └── strowallet-card.php # Virtual card API
│   │   ├── Kernel.php            # Middleware registry
│   │   └── Middleware/           # ~20 custom guards
│   ├── Models/                   # 40+ Eloquent models
│   ├── Services/
│   │   └── LoanCalculator.php    # Loan schedule engine
│   ├── Support/
│   │   └── BusinessDay.php       # Business day calculator
│   └── Traits/
│       ├── FundTransfer/         # OwnBankTransferTrait, OtherBankTransferTrait
│       └── PaymentGateway/       # 8 gateway traits
├── bootstrap/
├── config/                       # + app-specific: paystack, coinbase, geoip, paypal
├── database/
│   ├── migrations/               # ~60 files (2022–2026)
│   ├── seeders/                  # DatabaseSeeder + specific seeders
│   └── ibanking.sql              # Full DB dump
├── public/
│   ├── frontend/                 # Static assets (css, js, images, sass)
│   ├── build/                    # Vite output (deployed to Cloudflare Pages)
│   └── fileholder/               # User-uploaded files
├── resources/
│   ├── views/                    # Blade templates (admin/, user/, frontend/)
│   ├── installer/src/            # Custom web installer
│   ├── world/                    # JSON: countries, states, cities
│   ├── js/ + sass/               # Vite entry points
│   └── lang/                     # i18n files
├── routes/
│   ├── web.php                   # Root `/` closure (blocks route:cache)
│   ├── auth.php                  # User + admin auth
│   ├── user.php                  # Authenticated user routes (~200 lines)
│   ├── admin.php                 # Admin panel routes (~400 lines)
│   ├── frontend.php              # Public website
│   ├── global.php                # AJAX files & file uploads
│   ├── api/auth.php              # Mobile API auth
│   ├── api/global.php            # Mobile API global
│   ├── api/user.php              # Mobile API authenticated
│   └── channels.php              # Pusher broadcasting
├── storage/
├── tests/
│   ├── Unit/                     # LoanCalculatorTest, ExampleTest
│   └── Feature/                  # ExampleTest
└── vendor/
```

---

## Architecture & Data Flow

### Route Loading Order
`RouteServiceProvider` loads routes in this order:
1. `web.php` → `/` (closure — **breaks route:cache**)
2. `global.php` → AJAX/file endpoints
3. `frontend.php` → public pages
4. `auth.php` → login/register
5. `user.php` → `/user/*` authenticated
6. `admin.php` → `/admin/*` authenticated
7. `api/auth.php` → `/api/v1/user/auth`
8. `api/global.php` → `/api/v1/global`
9. `api/user.php` → `/api/v1/user` (Passport)

### Web Middleware Pipeline
```
SkipInstaller → EncryptCookies → StartSession → ShareErrors → VerifyCsrfToken
→ SubstituteBindings → Localization → ForceScheme → URLBlocker → StartingPoint → SecurityHardening
```

### Multi-Step Transaction Pattern (TemporaryData)
**Used by**: add money, fund transfer, money out, beneficiary, loans

1. **Submit** → `TemporaryData::create(...)` with unique `identifier`
2. **Create** → fetch by identifier, calc fees, show form
3. **Preview** → show charges, user confirms
4. **Execute** → `DB::transaction{}` creates Transaction, updates wallets, deletes TemporaryData

⚠️ **No garbage collection** — abandoned entries persist forever.

### Payment Gateways
- 8 **traits** (PayPal, Stripe, Flutterwave, CoinGate, Authorize, SslCommerz, Razorpay, PerfectMoney)
- `PaymentGateway` helper uses all traits
- Callbacks **strip all middleware** via `withoutMiddleware([...])` — identify user from payload, not session
- Config stored in `payment_gateways` + `payment_gateway_currencies` tables

### Admin RBAC
- 3 tables: `admin_roles` → `admin_role_permissions` → `admin_has_roles`
- Super admin bypass checks (`auth_is_super_admin()`)
- Route name = permission key
- Guarded by `admin.role.guard` middleware

### API (Passport OAuth)
- `User` model uses `HasApiTokens` trait
- Controllers in `App\Http\Controllers\Api\V1\User\`
- Guard: `api.user.auth.guard` → `Api\V1\User\AuthGuard`

---

## Middleware Reference

| Alias | Class | Purpose |
|-------|-------|---------|
| `app.mode` | `Admin\AppModeGuard` | Blocks POST/PUT/DELETE when `APP_MODE !== 'live'` |
| `kyc.verification.guard` | `KycVerificationGuard` | Requires KYC approval |
| `pin.setup.guard` | `User\PinSetupGuard` | Requires transaction PIN |
| `verification.guard` | `VerificationGuard` | Requires email/SMS verified |
| `user.google.two.factor` | `User\GoogleTwoFactor` | Requires 2FA |
| `system.maintenance` | `Admin\SystemMaintenance` | Maintenance mode block |
| `admin.role.guard` | `Admin\RoleGuard` | Role-based access control |
| `api.user.auth.guard` | `Api\V1\User\AuthGuard` | Passport token auth |
| `verification.guard.api` | `User\VerificationGuardApi` | API verification |
| `admin.login.guard` | `Admin\LoginGuard` | Guest guard for admin login |

---

## Conventions

### Status Codes
- `PaymentGatewayConst::STATUS*`: 1=success, 2=pending, 3=hold, 4=rejected, 5=waiting
- `GlobalConst`: 1=verified/approved/complete, 2=pending, 3=rejected

### Models
- **`$guarded = ['id']`** everywhere — all columns except `id` mass-assignable (inverted from Laravel default)
- `$casts` extensively used, especially `'data' => 'object'` and `'details' => 'object'`
- `$appends` for computed accessors: `fullname`, `userImage`, `stringStatus`, `lastLogin`, `kycStringStatus`
- `scopeAuth()` — common on user-scoped models
- Table names: snake_case plural (e.g., `temporary_datas`, `payment_gateway_currencies`)

### Global Helpers (autoloaded)
`app/Http/Helpers/helpers.php` (~1100+ lines) + `strowallet-card.php` — loaded on **every** request.

Key functions:
| Function | Purpose |
|----------|---------|
| `setRoute($name)` | Safe `route()`, returns `javascript:void(0)` in production if missing |
| `generateTrxString(...)` | Unique TX IDs (`FT-xxx`, `GC-xxx`) |
| `generate_unique_string(...)` | Random IDs for TemporaryData |
| `transactionDailyAndMonthlyLimitCheck()` | Per-transaction limit enforcement |
| `get_files_from_fileholder()` | File upload handling |
| `addMoneyChargeCalc($amount, $charges)` | Fee calc (fixed + percent × rate) |
| `auth_admin_permissions()` | Admin RBAC |
| `admin_permission_by_name($name)` | Check admin permission |
| `setPageTitle($title)` | Returns `"SiteName | Title"` |

### Flash Messages
```php
->with(['success' => ['Message here']])
->with(['error' => ['Something went wrong! Please try again.']])
->with(['warning' => ['Section under construction']])
```

### API Responses
```php
Response::success($msg, $data, $status) → 200 {message: {success: [...]}, data: ..., type: "success"}
Response::error($msg, $data, $status)   → 400 {message: {error: [...]}, data: ..., type: "error"}
Response::warning($msg, $data, $status)
```

---

## Critical Gotchas

1. **`APP_MODE` guard** — blocks POST/PUT/DELETE when not `'live'`. Checks `env("APP_MODE")` directly (not `config()`), survives `config:cache`. Has explicit allowlist in `AppModeGuard.php`.

2. **`routes/web.php` has a closure** — `php artisan route:cache` **will fail**. Use `deploy:optimize` instead.

3. **`TemporaryData` never garbage-collected** — abandoned entries persist forever.

4. **Gateway callbacks have no auth** — `withoutMiddleware()` strips everything. Identify user from payload.

5. **`deploy:backup` reads MySQL config** — but DB is PostgreSQL. Command will fail.

6. **User soft-delete** — sets `status=0` (banned), no PII removal.

7. **No test DB** — SQLite is commented out in `phpunit.xml`. DB tests need manual setup.

8. **`helpers.php` on every request** — cold-start impact.

9. **DB_CONNECTION=mysql in .env** — actual deployment uses pgsql. Known mismatch.

10. **CurrencySeeder truncates table** — `Currency::truncate()` before seeding. Custom currencies lost on `migrate:fresh --seed`.

---

## Key Services

### Loan Calculator (`app/Services/LoanCalculator.php`)
- 3 methods: `simple`, `compound`, `amortized`
- 3 frequencies: `monthly` (default), `biweekly`, `weekly`
- `generateSchedule()` — deletes + regenerates all payments in a DB transaction
- `applyLateFees()` — daily late fees with grace period
- Loan statuses: `pending`, `active`, `closed`, `defaulted`

### Singleton Providers
- `BasicSettingsProvider::get()` — global site settings
- `CurrencyProvider::default()` — default currency
- Both registered in `AppServiceProvider`

---

## Infrastructure

### Docker (`/home/ubuntu/docker/`)
- PHP 8.2-FPM, composer:2, pdo_pgsql/pgsql/gd/bcmath
- Volume: `/var/www/enzobank:/var/www/enzobank`
- Port 9000 localhost

### Cloudflare Pages
- `wrangler.toml` → deploys `public/build/`
- SPA not-found handling

### Vite
- Laravel Vite plugin, entries: `resources/sass/app.scss` + `resources/js/app.js`
- Output: `public/build/`

### Blue/Green Deployment
- `.env.blue` and `.env.green` for zero-downtime switching
- `.rnd` file (random seed — keep in .gitignore)

### Testing
- PHPUnit 9.x: Unit tests (pure PHPUnit), Feature tests (Laravel boot)
- No test DB — DB tests need manual setup
- Cache=array, Queue=sync in test env

---

## Secrets & Rules

- `.editorconfig`: 4-space indent (PHP/JS/CSS), 2-space YAML, LF, no trailing whitespace
- `composer.json` autoloads `helpers.php` and `strowallet-card.php` via `files` array
- `bun.lock` exists but `package.json` uses `npm run`
- 16 git commits, no branching strategy
- `helpers.php` is ~1100+ lines of global functions — read before adding new ones
