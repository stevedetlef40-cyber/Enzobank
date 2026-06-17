# EnzoBank: Comprehensive Migration & Deployment Guide

This guide outlines the professional protocols for migrating the EnzoBank (iBanking) platform from a local development environment (XAMPP/MAMP) to a production-grade cPanel hosting environment.

---

## 📋 Phase 1: Pre-Deployment Verification
Before initiating the migration, ensure the following checklist is completed:

- [ ] **PHP Version**: Server must run PHP 8.1 or higher.
- [ ] **Extensions**: Ensure `bcmath`, `ctype`, `fileinfo`, `json`, `mbstring`, `openssl`, `pcre`, `pdo`, `tokenizer`, `xml`, and `gd` are enabled in cPanel's Select PHP Version.
- [ ] **SSL Readiness**: Verify that AutoSSL is active for your domain.
- [ ] **Clean Logs**: Clear local logs in `storage/logs/*.log`.
- [ ] **Optimize Assets**: Run `npm run build` (if applicable) or ensure all compiled assets are in `public/`.

---

## 🗄️ Phase 2: Database Migration
A banking platform requires absolute data integrity during migration.

### Step 1: Export Local Database
- Open phpMyAdmin locally.
- Select the `ibanking` database.
- Use **Custom** export method:
  - Select all tables.
  - Ensure "Add DROP TABLE / VIEW / PROCEDURE / FUNCTION / EVENT / TRIGGER statement" is checked.
  - Save as `enzo_migration_prod.sql`.

### Step 2: Create cPanel Database
- Navigate to **MySQL® Database Wizard** in cPanel.
- Create database: `youruser_enzobank`.
- Create user: `youruser_admin`.
- Assign **ALL PRIVILEGES**.

### Step 3: Import to Production
- Open phpMyAdmin in cPanel.
- Import `enzo_migration_prod.sql`.
- **Validation**: Check table counts and verify the `users` and `wallets` tables are populated.

---

## 📁 Phase 4: File Transfer Protocol (Secure)
To maintain security, we do not upload the entire project to `public_html`.

### Step 1: Prepare the Bundle
- ZIP the following folders: `app`, `bootstrap`, `config`, `database`, `lang`, `public`, `resources`, `routes`, `storage`.
- **Exclude**: `node_modules`, `tests`, `.git`, `.env`.

### Step 2: Upload Above Root
- Upload the ZIP file to `/home/youruser/` (the root directory, **above** `public_html`).
- Extract the files into a folder named `enzobank_core`.

### Step 3: Public Directory Setup
- Move the contents of `enzobank_core/public` into `public_html`.
- Edit `public_html/index.php` and update paths:
  ```php
  require __DIR__.'/../enzobank_core/vendor/autoload.php';
  $app = require_once __DIR__.'/../enzobank_core/bootstrap/app.php';
  ```

---

## 🔐 Phase 5: Security Hardening & DNS
Banking standards require strict security protocols.

### 1. SSL/TLS Enforcement
- In cPanel, navigate to **SSL/TLS Status** and ensure the domain is green.
- Go to **Domains** -> **Force HTTPS Redirect** -> **On**.

### 2. Post-Deployment Verification
Run the following Artisan commands (via cPanel Terminal or Cron Job):
```bash
php artisan key:generate
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan storage:link
```

### 3. Automated Backups
Configure a Cron Job to run the built-in backup utility:
- **Command**: `php /home/youruser/enzobank_core/artisan deploy:backup`
- **Schedule**: Every 12 hours (0 0,12 * * *).

---

## 🚀 Phase 6: Post-Deployment Testing Protocol
Verify the following modules immediately after deployment:

1. **Authentication**: Test login, registration, and 2FA (if enabled).
2. **Transaction Integrity**: Perform a test transfer between two accounts.
3. **Responsive UI**: Check the dashboard on mobile to ensure zero-flicker theme loading.
4. **Notifications**: Verify that unread counts are updating correctly.
5. **Logs**: Monitor `storage/logs/laravel.log` for any runtime errors.

---

## 🛠️ Troubleshooting
- **500 Error**: Usually a permission issue. Ensure `storage` and `bootstrap/cache` are `775`.
- **CSS Not Loading**: Check if `APP_URL` in `.env` matches your domain (including https).
- **Symlink Error**: If user avatars are broken, run `php artisan storage:link`.

## 📈 Phase 7: Uptime & Performance Monitoring
To maintain 99.9% uptime and peak performance, implement the following:

### 1. External Monitoring (Recommended)
- **UptimeRobot**: Set up a free monitor to check your domain every 5 minutes.
- **Sentry**: Integrate Sentry.io to track runtime errors and exceptions automatically.
- **Cloudflare**: Use Cloudflare's free tier for DNS, CDN caching, and basic DDoS protection.

### 2. Internal Health Checks
The platform now includes a custom optimization command to be run after any updates:
```bash
php artisan deploy:optimize
```
This command automatically:
- Clears all cached data.
- Caches configuration, routes, and views.
- Re-verifies storage symlinks for media integrity.

---
*EnzoBank Infrastructure Protocol - Version 1.0.2*
