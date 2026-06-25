#!/bin/bash
set -e

echo "=============================="
echo "  BUNNA START.SH IS RUNNING"
echo "=============================="

php artisan config:clear
php artisan route:clear
php artisan cache:clear

echo ">>> Attempting DB connection..."
php artisan migrate --force && echo ">>> Migration OK" || echo ">>> Migration FAILED"
php artisan db:seed --force && echo ">>> Seeding OK" || echo ">>> Seeding FAILED"

echo ">>> Starting Apache..."
apache2-foreground