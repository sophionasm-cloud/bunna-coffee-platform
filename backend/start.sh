#!/bin/bash
set -e

echo "=============================="
echo "  BUNNA START.SH IS RUNNING"
echo "=============================="

echo ">>> Running migrations..."
php artisan migrate --force && echo ">>> Migration OK" || echo ">>> Migration FAILED"

echo ">>> Running seeders..."
php artisan db:seed --force && echo ">>> Seeding OK" || echo ">>> Seeding FAILED"

echo ">>> Starting Apache..."
apache2-foreground