# EasyBooking frontend refresh

This update redesigns the Laravel Blade frontend while keeping the existing Laravel application, database schema, models, migrations, seed data and event routes.

## Apply the update

1. Back up your current `EasyBooking` folder.
2. Extract this ZIP. Copy the contents of the included `EasyBooking` folder into your existing `C:\laragon\www\EasyBooking` folder and allow the updated files to replace the old ones.
3. **Keep your existing `.env` file.** It is intentionally not included in this ZIP because it contains local database settings and secrets.
4. Open a terminal in `C:\laragon\www\EasyBooking` and run:

```bash
npm install
npm run build
php artisan view:clear
php artisan optimize:clear
```

5. Refresh `http://127.0.0.1:8000`.

If the Laravel server is not running, start it with `php artisan serve`.

## What changed

- Rebuilt the shared navigation and footer, homepage, event listing, event details, About page and Organiser page.
- Replaced the inconsistent event-card markup with a reusable responsive Blade component.
- Added responsive navigation for mobile screens.
- Kept event data dynamic and connected to the existing database.
- Preserved the current ticket placeholder. Checkout, payments, issuing tickets and organiser tools are not implemented in this frontend update.

The project ZIP excludes `.env`, `vendor` and `node_modules`. Your local dependencies and database remain yours; the commands above rebuild the frontend assets.
