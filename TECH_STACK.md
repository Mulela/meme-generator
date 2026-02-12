# Meme Generator – SUPINFO Admission Project

## Tech Stack
- Laravel Framework 12.51.0
- PHP 8.4.5
- SQLite (development)
- Node 24.13.0
- Tailwind CSS (via Vite)

## Setup

1. Install dependencies
   composer install
   npm install

2. Generate key
   php artisan key:generate

3. Run migrations
   php artisan migrate

4. Create storage link (if not exists)
   php artisan storage:link

5. Run dev server
   php artisan serve
   npm run dev
