# Laravel 11 API Project

## Requirements
- PHP v8.2
- Laravel v11
- Composer
- Postgres v16.10
- Redis

## Packages
- https://scribe.knuckles.wtf/laravel/
- https://spatie.be/docs/laravel-data/v4/introduction

---

## Installation & Setup

### 1. Setup
   ```bash
   git clone https://github.com/your/repo.git
   cd news-aggregator-backend
   composer install
   cp .env.example .env
   php artisan key:generate
   php artisan migrate
   php artisan db:seed --class=SourceSeeder
   php artisan scribe:generate --force
   php artisan serve
```
### 2. Config
   ```bash
    DB_CONNECTION=pgsql
    DB_HOST=127.0.0.1
    DB_PORT=5432
    DB_DATABASE=news_aggregator
    DB_USERNAME=postgres
    DB_PASSWORD=yourpassword
    QUEUE_CONNECTION=database|redis
    NEWSAPI_KEY=your-key
    GUARDIAN_KEY=your-key
    NYT_KEY=your-key
```
### 3. Run the scheduler And queue workers
```bash
   php artisan schedule:work
   php artisan queue:work
```
### 4. Check Logs
```bash
tail -f storage/logs/news.log
   ```
### 5. API Documentation

After generating, open your browser and visit:
http://localhost:8000/docs

### 6. To Dos
    - Implement Category relation/persist into DB categories table
    - Integration Testing with Pest
