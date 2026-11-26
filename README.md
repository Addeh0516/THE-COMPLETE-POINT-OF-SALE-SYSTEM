# Afterhours Arcade POS

Afterhours Arcade is a Laravel 13 point-of-sale system for a late-night arcade counter. It combines the laboratory requirements from Modules 1–4—routing/MVC structure, a relational data layer, validated CRUD forms and safe uploads, sessions/authentication—with the final transaction workflow: recording a sale and decrementing inventory safely.

## Included

- Staff login/logout with hashed passwords and `auth`-protected management routes.
- Product CRUD with price, stock quantity, and validated display-ready image upload.
- Customer CRUD with optional phone number.
- Staff CRUD with validated avatar upload and password confirmation.
- Record Sale flow with optional customer selection, transaction locking, stock validation, and rollback when quantity exceeds stock.
- Sales history showing product, customer, staff member, quantity, total, and date.
- Seed data and a late-night arcade UI using the supplied arcade photography in `public/media/`.

New product images and staff avatars are stored with randomized filenames under `public/uploads/`, so they display through ordinary Apache/XAMPP public URLs. Existing files saved by an older version under `storage/app/public/` can be made visible once with `php artisan storage:link`.

## Local setup (XAMPP / PHP)

Requirements: PHP 8.3+, Composer, and SQLite or MySQL. The included local environment defaults to SQLite so it runs without creating a separate database service.

```powershell
cd "C:\xampp\htdocs\THE COMPLETE POINT-OF-SALE SYSTEM"
composer install
copy .env.example .env
php artisan key:generate
php artisan migrate --seed
php artisan storage:link
php artisan serve
```

Open `http://127.0.0.1:8000`.

Seeded staff accounts:

- `arcade.admin` / `midnight123`
- `floor.runner` / `midnight123`

Change seeded credentials before deploying anywhere public.

## MySQL / XAMPP database

Create a database such as `afterhours_arcade` in phpMyAdmin, then set these values in `.env` before running migrations:

```dotenv
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=afterhours_arcade
DB_USERNAME=root
DB_PASSWORD=
```

Then run `php artisan migrate --seed`. The migration files in `database/migrations/` are the database export/source of truth and preserve the required product, customer, user, and sales relationships.

## Verification

```powershell
php artisan test
php artisan route:list
php artisan view:cache
```

The feature tests cover guest route protection, a successful stock decrement, and rejection/rollback of an over-stock sale.

## Notes on the visual system

`DESIGN.md` records the durable palette, typography, layout grammar, signature scanline ledger, and interaction rules. `PRODUCT.md` records the product truth and constraints. The runtime theme is in `public/css/arcade.css`; the Blade views consume that shared token system.

This repository is intentionally local at this stage. Review the local app first; no GitHub repository or remote publish has been created by this build.
