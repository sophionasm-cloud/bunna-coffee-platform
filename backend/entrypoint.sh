#!/bin/bash

# Fix storage permissions
chown -R www-data:www-data /var/www/html/storage
chown -R www-data:www-data /var/www/html/bootstrap/cache
chmod -R 777 /var/www/html/storage
chmod -R 777 /var/www/html/bootstrap/cache

# Run migrations
php artisan migrate --force || true
php artisan db:seed --force || true

# Start Apache
apache2-foreground