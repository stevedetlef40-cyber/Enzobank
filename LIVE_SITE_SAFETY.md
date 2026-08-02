# 🚨 LIVE SITE SAFETY PROTOCOL (MANDATORY FOR ALL AI AGENTS)

**This site is LIVE** (`enzobank.org`, real users, real money flows, production PostgreSQL).
Every single change — no matter how small — MUST pass the full verification sequence below.
**If you cannot verify a change end-to-end, do NOT commit or push it.**

---

## 1. Golden Rules

1. **Never break the live site.** If a change risks availability, correctness, or data,
   stop and reconsider before proceeding.
2. **Never edit a file you have not read** in the current session (relevant context is enough).
3. **Verify after EVERY change** — never batch a "fix" with "test later".
4. **Tests hit the real production database.** Treat every query as destructive by default.
5. **No scratch files.** Delete any `test_*.php`, `debug*.php`, or temp scripts before committing.
6. **No silent rollbacks.** If you revert a change, say why and verify after reverting.
7. **APP_DEBUG is intentionally ON** so errors surface in `storage/logs/laravel.log`.
   Use it to find errors — never to skip fixing them.

---

## 2. Pre-Flight Checklist (before touching anything)

- [ ] Read `AGENTS.md` (this project guide) fully — architecture, conventions, gotchas.
- [ ] Read `LIVE_SITE_SAFETY.md` (this file) fully.
- [ ] Confirm repo state: `git status`, `git log --oneline -3`.
- [ ] Confirm the app is currently healthy:
      `docker exec enzobank_php bash -c "tail -20 /var/www/enzobank/storage/logs/laravel.log"`
      (should have NO `local.ERROR` entries from the last minutes)
- [ ] Note the current log size/mtime so you can detect NEW errors later:
      `docker exec enzobank_php bash -c "stat -c '%y %s' /var/www/enzobank/storage/logs/laravel.log"`
- [ ] Identify every file the change touches (views, controllers, routes, migrations, helpers).
- [ ] Read the exact context (indentation, whitespace) of every file before editing.

---

## 3. Mandatory Verification Sequence (run after EVERY change)

Run ALL steps in order. Any failure = fix it before continuing.

### Step 1 — Blade compile check (host)
```bash
cd /var/www/enzobank && php artisan view:cache && php artisan view:clear
```
Catches Blade syntax/compile errors immediately.

### Step 2 — PHPUnit (host)
```bash
cd /var/www/enzobank && ./vendor/bin/phpunit 2>&1 | tail -3
```
Must end with `OK`. Fix any failing test before moving on.

### Step 3 — PHPUnit (container)
```bash
docker exec -i enzobank_php bash -c "cd /var/www/enzobank && vendor/bin/phpunit 2>&1 | tail -3"
```

### Step 4 — Reload the live container
```bash
docker exec enzobank_php bash -c "cd /var/www/enzobank && chown -R www-data:www-data storage/framework/views bootstrap/cache storage/app && su -s /bin/sh www-data -c 'php artisan view:clear' && kill -USR2 1"
```

### Step 5 — HTTP smoke test the affected routes
Render each affected page as an authenticated request and expect HTTP 200:
```bash
cd /var/www/enzobank && php -r '
require "vendor/autoload.php";
$app = require "bootstrap/app.php";
$app->make("Illuminate\Contracts\Console\Kernel")->bootstrap();
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$user = App\Models\User::where("email", "test-one@enzobank.org")->first();
Auth::login($user);
$names = ["user.rise.home", "user.rise.send", "user.transactions.index", "user.bank.details.index"];
foreach ($names as $n) {
  $req = Illuminate\Http\Request::create(route($n), "GET");
  $req->setLaravelSession($app->make("session.store"));
  try { $r = $kernel->handle($req); echo "$n: " . $r->getStatusCode() . "\n"; }
  catch (\Throwable $e) { echo "$n: EXCEPTION " . $e->getMessage() . "\n"; }
  $kernel->terminate($req, $r ?? null);
}
'
```
For PDF routes, also assert `content-type: application/pdf` and 1 page for receipts.

### Step 6 — Verify the log is still clean
```bash
docker exec enzobank_php bash -c "grep -c 'local.ERROR' /var/www/enzobank/storage/logs/laravel.log; stat -c '%y %s' /var/www/enzobank/storage/logs/laravel.log"
```
Zero new errors. If entries appeared, fix them before committing.

### Step 7 — Commit + push only after ALL steps pass
```bash
git add -A && git commit -m "..." && git push origin main && git log --oneline -1
```

---

## 4. Forbidden Operations on the Live Site

| Operation | Why it's forbidden |
|---|---|
| `php artisan migrate:fresh` / `migrate:refresh` / `db:wipe` | Destroys the production database. **Never.** |
| `Currency::truncate()` / any table truncate | CurrencySeeder truncates — running it on live wipes custom currencies. |
| `php artisan route:cache` | Fails — `routes/web.php` contains a closure. Use `deploy:optimize`. |
| Raw SQL `UPDATE` / `DELETE` on live tables | Destructive and unreviewable. If needed, use `deploy:backup` first + transaction + WHERE with verification. |
| Changing `APP_MODE` / `APP_DEBUG` / DB creds in `.env` | `APP_MODE` must stay `live` (AppModeGuard blocks POST otherwise). `.env` is not committed. |
| Committing secrets / API keys | Never log or commit credentials, tokens, or private keys. |
| `deploy:backup` | Known broken — reads MySQL config while DB is Postgres. Don't rely on it for rollback. |
| Leaving `test_*.php` / `debug*.php` at repo root | These get committed and confuse future agents. Delete before commit. |
| Force-pushing / rewriting history | `main` is shared with production deployment. |

---

## 5. Rollback (if something breaks)

1. **Immediately stop the bleeding**: revert the offending file(s):
   ```bash
   git checkout -- <file>        # discard uncommitted change
   # or
   git revert HEAD --no-edit     # if already committed
   ```
2. Clear caches and reload:
   ```bash
   php artisan view:clear
   docker exec enzobank_php bash -c "cd /var/www/enzobank && chown -R www-data:www-data storage/framework/views bootstrap/cache && su -s /bin/sh www-data -c 'php artisan view:clear' && kill -USR2 1"
   ```
3. Re-run Steps 2, 3, 5, 6 from Section 3 to confirm the site is healthy again.
4. For DB-related breakage: restore from the latest `ibanking.sql` dump or a
   pre-change `pg_dump` snapshot. Never re-run migrations on live data blindly.
5. Report the rollback clearly in your final message (what broke, what you reverted).

---

## 6. Live-Specific Gotchas (from AGENTS.md)

- **`APP_MODE=live`** — AppModeGuard blocks POST/PUT/DELETE if it's ever changed. Keep it `live`.
- **Tests hit real Postgres** — never write a test that truncates, deletes all, or mutates
  shared records. Prefer `firstOrCreate` / dedicated test users and clean up after yourself.
- **`Transaction::details`** may be a JSON **string** OR an **object** — always
  `is_string($d) ? json_decode($d) : $d` before property access.
- **Blade `{{ }}` escapes HTML entities** — `&mdash;`, `&#8595;` etc. render as literal
  text inside `{{ }}`. Use raw HTML or literal unicode characters instead.
- **DomPDF limitations** — no `transform: translate()`, no `repeating-conic-gradient`,
  no `inset` shorthand. Use negative margins / plain boxes / explicit top-right-bottom-left.
- **`$errors` is only present with session middleware** — CLI renders of views must
  `view()->share("errors", new Illuminate\Support\ViewErrorBag)` or go through HTTP kernel.
- **`route:cache` breaks** on the `/` closure in `routes/web.php`.
- **User soft-delete** only sets `status=0`; no PII removal.
- **Helper functions defined in Blade `@php` blocks** must be guarded with
  `if (!function_exists(...))` or they crash on double render.
- **Verify route names before using them in Blade** — `php artisan route:list | grep <name>`;
  a wrong name 500s the whole page.

---

## 7. Good Practice for AI Agents

- Make ONE logical change at a time; verify after each.
- Prefer surgical `edit`/`multiedit` over full-file rewrites of existing files.
- Follow existing code patterns (see AGENTS.md conventions) — don't invent new styles.
- Keep responses concise; but report anything you fixed, rolled back, or left unfixed.
- If you hit a blocker twice, STOP and revert rather than guessing on a live site.
- When in doubt about a destructive action, ask the user before proceeding.
