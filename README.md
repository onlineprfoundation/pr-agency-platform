# Online PR – Agency Platform

A self-hosted Laravel application for PR agencies. Manage clients, projects, publications, invoicing, and a client portal—all configurable via admin settings. Distribution-ready with web installer, Stripe, SMTP, and optional reCAPTCHA.

## Features

### Public Site
- Homepage with customizable hero, packages, and publications
- Packages and publications listing
- Contact form and Request Quote form
- Custom pages (About, Terms, Privacy, etc.) at `/p/{slug}`

### Admin Dashboard
- **Leads** – CRM for contact and quote submissions
- **Clients** – Client management with portal invite
- **Projects** – Projects with publications, documents, messages, invoicing
- **Packages** – Package pricing and Stripe checkout
- **Publications** – Outlet placements
- **Payments** – Payment history
- **Pages** – Custom page CMS
- **Modules** – Enable/disable modules (marketplace-ready)
- **Settings** – Full configuration UI

### Project Management
- Clients select one or multiple publications per project
- Document upload (PDF, DOC, images) for project files
- Style guide and messaging
- Order workflow (draft → active → review → completed)

### Client Portal
- Clients log in to view their projects
- Upload documents and send messages
- Download project files
- Invite flow: Admin invites client by email; they set password via reset link

### Stripe Invoicing
- Create draft invoices per project
- Send payment links (Stripe Checkout)
- Webhook for payment confirmation
- Package checkout for public purchases

### Admin Settings (Distribution-Ready)
- **General** – Site name, tagline, contact info, logo, favicon upload
- **Payment** – Stripe keys, webhook URL, currency
- **Email** – SMTP host, port, credentials, from address
- **Homepage** – Hero title, subtitle, CTA, section toggles
- **Pages** – Link to page management
- **Security** – Google reCAPTCHA (contact, quote, login forms)

### Module Marketplace
- UI to browse and enable/disable modules
- Architecture for future exclusive module marketplace (official modules by Online PR)

### Attribution
Footer displays "Powered by Online PR" (required by license).

---

## Requirements

- PHP 8.2+
- Composer
- Node.js & npm
- MySQL, PostgreSQL, or SQLite

---

## Installation

### Option 1: Web Installer (Recommended)

1. Clone or download the repo
2. Run:
   ```bash
   composer install
   npm ci
   npm run build
   php artisan serve
   ```
3. Visit `http://localhost:8000` – you'll be redirected to the installer
4. Follow: **Requirements** → **Database** → **Administrator** → **Done**
5. The installer creates the database, runs migrations, creates storage link, and your admin account

### Option 2: Manual Installation

```bash
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate
php artisan storage:link
npm ci
npm run build
php artisan serve
```

Create an admin user manually or run the installer by deleting `storage/installed`.

---

## Configuration

Most settings are configurable in **Admin → Settings**:

| Tab | Configurable |
|-----|--------------|
| General | Site name, tagline, contact email/address, logo, favicon |
| Payment | Stripe publishable key, secret, webhook secret, currency |
| Email | Mail driver, SMTP host/port/username/password, from address |
| Homepage | Hero title, subtitle, CTA text, show packages/publications |
| Security | reCAPTCHA (enable, site key, secret, forms: contact, quote, login) |

You can also use `.env`; settings in the database override env values.

---

## Environment Variables

See `.env.example`. Key variables:

- `DB_*` – Database connection
- `STRIPE_KEY`, `STRIPE_SECRET`, `STRIPE_WEBHOOK_SECRET` – Stripe (or set in Settings)
- `MAIL_*` – SMTP (or set in Settings)
- `RECAPTCHA_SITE_KEY`, `RECAPTCHA_SECRET_KEY` – reCAPTCHA (or set in Settings)

---

## Deployment

### cPanel / Shared Hosting

See **[docs/INSTALL_CPANEL.md](docs/INSTALL_CPANEL.md)** for step-by-step instructions:

1. Build deploy ZIP: `composer install --no-dev && npm run build` then zip (or use `php scripts/build-deploy-zip.php`)
2. Create MySQL database in cPanel
3. Upload and extract to your hosting
4. Set document root to `public` folder (or use root `.htaccess` to redirect)
5. Visit `https://yourdomain.com/install` and follow the wizard

---

### VPS / Dedicated Server

```bash
composer install --no-dev --optimize-autoloader
php artisan migrate --force
php artisan storage:link
npm ci
npm run build
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

Set `APP_ENV=production` and `APP_DEBUG=false` in `.env`.

---

## Pushing to GitHub

### Step 1: Initialize Git (if not already)

```bash
cd /path/to/agency-platform
git init
```

### Step 2: Add Files and Commit

```bash
git add .
git commit -m "Initial commit: Online PR agency platform"
```

### Step 3: Create Repository on GitHub

1. Go to [github.com/new](https://github.com/new)
2. Create a new repository (e.g. `online-pr-agency-platform`)
3. Do **not** initialize with README (you already have one)

### Step 4: Connect and Push

```bash
git remote add origin https://github.com/YOUR_USERNAME/YOUR_REPO_NAME.git
git branch -M main
git push -u origin main
```

### Step 5: Using SSH (Optional)

If you use SSH keys:

```bash
git remote add origin git@github.com:YOUR_USERNAME/YOUR_REPO_NAME.git
git push -u origin main
```

### Step 6: GitHub CLI (Alternative)

If you have [GitHub CLI](https://cli.github.com/) installed:

```bash
gh auth login
gh repo create online-pr-agency-platform --public --source=. --push
```

---

## License & Attribution

MIT License with attribution clause. You must retain the "Powered by Online PR" credit in the public footer. See [LICENSE](LICENSE) for full terms.

---

## Links

- [License](LICENSE)
- [Contributing](CONTRIBUTING.md)
- [Changelog](CHANGELOG.md)
