# iBanking cPanel Hosting & Deployment Guide

This guide provides step-by-step instructions for deploying the iBanking platform to a cPanel-based hosting environment.

## 1. Domain & SSL Setup
- Log in to your cPanel.
- Navigate to **Domains** and ensure your primary domain or subdomain is correctly added.
- Go to **SSL/TLS Status** and run "AutoSSL" to ensure a valid certificate is installed.
- Ensure the domain root points to the `public_html` directory (or a subdirectory for subdomains).

## 2. Database Creation
- Navigate to **MySQL® Database Wizard**.
- Create a new database (e.g., `youruser_ibanking`).
- Create a new database user and generate a secure password.
- Assign the user to the database with **ALL PRIVILEGES**.
- Note down the Database Name, Username, and Password.

## 3. File Upload Procedure
- Compress the project files into a `.zip` archive.
- **IMPORTANT**: Exclude the `node_modules`, `vendor`, and `.git` directories to reduce file size.
- In cPanel, open **File Manager**.
- Upload the `.zip` file to your root directory (above `public_html` is recommended for security).
- Extract the archive.
- Move the contents of the `public` directory into `public_html`.
- Update `index.php` in `public_html` to point to the correct paths:
  - `require __DIR__.'/../vendor/autoload.php';`
  - `$app = require_once __DIR__.'/../bootstrap/app.php';`

## 4. Environment Configuration
- Locate the `.env` file in the project root.
- Update the following variables:
  - `APP_ENV=production`
  - `APP_DEBUG=false`
  - `APP_URL=https://yourdomain.com`
  - `DB_DATABASE=youruser_ibanking`
  - `DB_USERNAME=youruser_ibanking_user`
  - `DB_PASSWORD=your_secure_password`
  - `FORCE_HTTPS=true`

## 5. Database Migration & Import
- Navigate to **phpMyAdmin** in cPanel.
- Select your new database.
- Go to the **Import** tab.
- Upload and import the `database/ibanking (1).sql` file provided in the package.
- Alternatively, if you have SSH access, run:
  ```bash
  php artisan migrate --force
  ```

## 6. Post-Deployment Verification
- Visit your domain in a browser.
- Verify the Login and Registration pages load correctly.
- Test the Light/Dark mode toggle.
- Check the dashboard widgets for data rendering.
- Verify that CSS and JS assets are loading correctly (check browser console for 404s).

## 7. Automated Backups
- The system includes a built-in backup utility.
- Set up a **Cron Job** in cPanel:
  - Command: `php /path/to/your/project/artisan deploy:backup`
  - Frequency: Daily at midnight.

## 8. Troubleshooting
- **500 Internal Server Error**: Check the `.htaccess` file in `public_html` and the Laravel logs in `storage/logs/laravel.log`.
- **Permission Denied**: Ensure `storage` and `bootstrap/cache` directories are writable (chmod 775 or 755).
- **Symlink Issues**: If images aren't loading, run the `symlink.php` script included in the root.

---
*Banking Site Architect - Professional Infrastructure Configuration*
