#!/bin/sh
set -e

php artisan config:clear

echo "Running database migrations..."
php artisan migrate --force

echo "Seeding roles, permissions and default users (safe to run every boot)..."
php artisan db:seed --class=RoleSeeder --force

echo "Linking storage..."
php artisan storage:link || true

echo "Caching config/routes/views..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo "Starting Apache..."
exec apache2-foreground
