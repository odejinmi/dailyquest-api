# DailyQuest API

This is the backend API for the DailyQuest application.

## Requirements

- PHP 8.0+
- Composer
- MySQL 5.7+ or PostgreSQL 10+
- Redis (optional, for improved caching)
- Firebase project (for push notifications)

## Installation

1. Clone the repository:


2. Install dependencies:
   composer install --optimize-autoloader --no-dev

3. Create environment file:
   cp .env.example .env
   php artisan key:generate


4. Configure your database in `.env`

5. Run migrations and seed the database:
   php artisan migrate --seed

6. Set up storage symlink:
   php artisan storage:link

7. Configure Firebase (for push notifications):
- Place your Firebase credentials JSON file in the project root
- Update the `FIREBASE_CREDENTIALS` in `.env`

8. Set up scheduled tasks:
   Add this Cron entry to your server:
   * * * * * cd /path-to-your-project && php artisan schedule:run >> /dev/null 2>&1

9. Configure web server (Nginx example):
   ```
   server {
   listen 80;
   server_name api.dailyquest.com;
   root /path-to-your-project/public;
   add_header X-Frame-Options "SAMEORIGIN";
   add_header X-Content-Type-Options "nosniff";

   index index.php;

   charset utf-8;

   location / {
   try_files $uri $uri/ /index.php?$query_string;
   }

   location = /favicon.ico { access_log off; log_not_found off; }
   location = /robots.txt  { access_log off; log_not_found off; }

   error_page 404 /index.php;

   location ~ \.php$ {
   fastcgi_pass unix:/var/run/php/php8.0-fpm.sock;
   fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
   include fastcgi_params;
   }

   location ~ /\.(?!well-known).* {
   deny all;
   }
    }
   ```

10. Set up SSL with Let's Encrypt (recommended for production)

## API Documentation

API documentation is available at `/api/documentation` when the application is running.

## Health Check

A health check endpoint is available at `/api/health` to monitor the application status.

## License

This project is licensed under the MIT License - see the LICENSE file for details.

