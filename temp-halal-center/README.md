# Halal Center Kaltim

Laravel 13 single-page institutional website + custom admin dashboard without Filament.

## Stack

- Laravel 13
- MySQL (Laragon `halal_center`)
- Blade + Tailwind + Alpine
- Leaflet for WebGIS
- Swiper for hero slider
- Quill CDN for WYSIWYG admin editor
- Sanctum-ready API
- Spatie Permission
- Spatie Activitylog
- Eloquent Sluggable
- Purify

## Clean Architecture

- `app/Http/Controllers/Admin` : custom CRUD dashboard controllers
- `app/Http/Controllers/API` : API-only controllers with standard JSON response
- `app/Http/Requests` : form request validation
- `app/Http/Resources` : API resources
- `app/Models` : domain models
- `app/Services` : landing page, search, and media storage logic
- `app/Support/ApiResponse.php` : JSON response standard
- `resources/views/layouts` : main public/admin layouts
- `resources/views/components` : reusable navbar/footer
- `resources/views/home/index.blade.php` : one-page public landing page
- `resources/views/admin/crud` : reusable admin index/form views

## Main CRUD Modules

- Site setting / institutional profile
- Regions / district statistics
- Program slides
- Articles / publications / research
- WebGIS halal locations
- Organization members
- Certification paths
- Halal products directory
- Mentors / PPH companions
- Knowledge resources / e-library
- Regulations
- Events / agenda
- Gallery
- FAQ
- Consultation requests

## Storage Rules

- Public media: `storage/app/public`
- Private documents: `storage/app/private`
- Public symlink: `php artisan storage:link`

## Demo Accounts

- Admin: `admin@halalcenter.test` / `password`
- Editor: `editor@halalcenter.test` / `password`

## Commands

```bash
php artisan migrate:fresh --seed
php artisan test
npm run build
php artisan serve
```

## API Endpoints

- `GET /api/home`
- `GET /api/map`
- `GET /api/articles`
- `GET /api/products`
- `GET /api/mentors`
- `GET /api/search?keyword=halal`

## Notes

- Public page is designed as a single uninterrupted scroll experience.
- Dynamic map markers and region statistics are powered from database data.
- Private documents are downloaded through Laravel controller routes, not exposed directly.
