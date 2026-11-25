# EasyBooking

EasyBooking is a Laravel 12 event discovery frontend. It includes a responsive homepage, event discovery/search, category filters, event detail pages, and organiser/about information pages.

## Requirements

- PHP 8.2+
- Composer
- Node.js and npm
- MySQL (Laragon is suitable)

## Existing local installation

If you are updating the EasyBooking project already installed in Laragon, **keep your existing `.env` file**. Copy the project files over your existing folder, then run:

```bash
npm install
npm run build
php artisan view:clear
php artisan optimize:clear
```

Start Laravel if it is not already running:

```bash
php artisan serve
```

Open http://127.0.0.1:8000.

## Fresh installation

1. Create a MySQL database named `easybooking`.
2. Copy `.env.example` to `.env` and update the database credentials if needed.
3. Run:

```bash
composer install
php artisan key:generate
php artisan migrate --seed
npm install
npm run build
php artisan serve
```

## Current implementation status

The event pages use the existing `events` and `ticket_types` tables and the existing sample event seeder. Ticket prices are displayed, but checkout, payment processing, customer accounts, ticket issuing/QR scanning, organiser dashboards and event publishing are not implemented yet. The booking button is intentionally disabled.

## Frontend refresh

The redesign uses Blade templates and a responsive custom CSS system. It does not replace the Laravel backend or database. See `README-REDESIGN.md` for the update summary.
