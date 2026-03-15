# Installing Online PR on cPanel / Shared Hosting

Step-by-step guide to deploy the agency platform on cPanel, Plesk, or similar shared hosting.

---

## Prerequisites

- **PHP 8.2+** (check in cPanel → MultiPHP Manager)
- **MySQL 5.7+** or **MariaDB 10.3+**
- **Composer** (for building the ZIP) – only needed on your local machine or a build server

---

## Step 1: Create a Deployable ZIP

On your **local machine** (with Composer and Node.js installed):

```bash
cd /path/to/agency-platform

# Install dependencies (production only)
composer install --no-dev --optimize-autoloader

# Build frontend assets
npm ci
npm run build

# Create deploy ZIP (excludes dev files, includes vendor and build)
zip -r online-pr-deploy.zip . \
  -x "*.git*" \
  -x "node_modules/*" \
  -x ".env" \
  -x "*.log" \
  -x ".DS_Store" \
  -x "tests/*" \
  -x "phpunit.xml" \
  -x ".phpunit.result.cache"
```

Or use the provided script (run after `composer install` and `npm run build`):

```bash
composer install --no-dev --optimize-autoloader
npm ci && npm run build
php scripts/build-deploy-zip.php
```

The ZIP `online-pr-deploy.zip` will be created in the project root. Upload it to your hosting.

---

## Step 2: Create MySQL Database

1. Log in to **cPanel**
2. Go to **MySQL Databases**
3. **Create a new database** (e.g. `username_agency`)
4. **Create a database user** (e.g. `username_agencyuser`) with a strong password
5. **Add user to database** with **All Privileges**
6. Note down:
   - **Database name:** `username_agency`
   - **Username:** `username_agencyuser`
   - **Password:** (your chosen password)
   - **Host:** Usually `localhost` (cPanel shows this)

---

## Step 3: Upload and Extract

### Option A: Subdomain (Recommended)

1. In cPanel, create a **subdomain** (e.g. `app.yourdomain.com`)
2. Note the folder path (e.g. `public_html/app` or `home/user/app.yourdomain.com`)
3. Go to **File Manager** → navigate to that folder
4. **Upload** `online-pr-deploy.zip`
5. **Extract** the ZIP
6. Delete the ZIP after extraction

### Option B: Addon Domain

1. Add an **addon domain** (e.g. `agency.yourdomain.com`)
2. Upload and extract the ZIP to that domain's folder

### Option C: Main Domain in Subfolder

1. Upload ZIP to a folder (e.g. `public_html/agency`)
2. Extract
3. You'll need to point the document root to the `public` subfolder (see Step 4)

---

## Step 4: Set Document Root

Laravel requires the web root to be the `public` folder.

### In cPanel (Domains / Subdomains)

1. Go to **Domains** → **Domains** (or **Subdomains**)
2. Find your domain/subdomain → **Manage** or **Document Root**
3. Set document root to:
   ```
   /home/username/public_html/app/public
   ```
   (Replace `username` and `app` with your actual path)

### If You Can't Change Document Root

Create an `.htaccess` in the **root folder** (where you extracted) to redirect to `public`:

```apache
<IfModule mod_rewrite.c>
    RewriteEngine On
    RewriteRule ^(.*)$ public/$1 [L]
</IfModule>
```

Then ensure `public/.htaccess` exists (it's included in the ZIP).

---

## Step 5: Set Permissions

Via **File Manager** or **SSH**:

```bash
chmod -R 755 storage bootstrap/cache
chmod -R 775 storage bootstrap/cache
```

Or in cPanel File Manager: right-click `storage` and `bootstrap/cache` → **Change Permissions** → set to `755` or `775`.

---

## Step 6: Run the Web Installer

1. Visit your site URL: `https://app.yourdomain.com` (or your domain)
2. You'll be redirected to the **installer**: `https://app.yourdomain.com/install`
3. Follow the steps:

   **a) Requirements** – Check PHP version and extensions  
   **b) Database** – Enter:
   - Connection: MySQL
   - Host: `localhost`
   - Database: `username_agency`
   - Username: `username_agencyuser`
   - Password: (your DB password)

   **c) Administrator** – Create your admin account:
   - Name, email, password

   **d) Complete** – Installation finishes. Delete or restrict access to `/install` if desired.

4. Log in at `https://app.yourdomain.com/login`

---

## Step 7: Post-Installation

### Storage Link

The installer runs `php artisan storage:link`. If uploads (logo, documents) don't work:

- Use **SSH** (if available): `php artisan storage:link`
- Or create a symlink manually in File Manager: `public/storage` → `../storage/app/public`

### Cron Job (Optional)

For queue workers, scheduled tasks, or email:

1. cPanel → **Cron Jobs**
2. Add: `* * * * * cd /home/username/path/to/app && php artisan schedule:run >> /dev/null 2>&1`

---

## Troubleshooting

### 500 Internal Server Error

- Check `storage/logs/laravel.log`
- Ensure `storage` and `bootstrap/cache` are writable (775)
- Verify PHP 8.2+ and required extensions: `pdo`, `mbstring`, `openssl`, `tokenizer`, `xml`, `ctype`, `json`, `bcmath`, `fileinfo`

### Installer Not Loading

- Confirm document root points to `public`
- Check `.htaccess` exists in `public/`
- Ensure `mod_rewrite` is enabled (contact host if needed)

### Database Connection Failed

- Use `localhost` (not `127.0.0.1`) for MySQL host
- Confirm database user has full privileges
- Some hosts use a different MySQL host – check cPanel MySQL section

### CSS/JS Not Loading

- Re-run `npm run build` and re-upload the `public/build` folder
- Clear browser cache

---

## Security Notes

- Delete or password-protect the `/install` folder after installation
- Use **HTTPS** (SSL) – most cPanel hosts offer free Let's Encrypt
- Keep `vendor` and `.env` outside the web root if your host allows custom document roots

---

## Quick Reference

| Item | Example |
|------|---------|
| Installer URL | `https://yourdomain.com/install` |
| Login URL | `https://yourdomain.com/login` |
| Document root | `.../public` |
| Database host | `localhost` |
