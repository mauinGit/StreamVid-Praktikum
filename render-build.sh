#!/bin/bash

# Build steps for Render
echo "Installing composer dependencies..."
composer install --no-dev --optimize-autoloader

echo "Copying .env if not exists..."
cp .env.example .env 2>/dev/null || true

echo "Generating app key..."
php artisan key:generate --force

echo "Installing Node.js dependencies..."
npm ci

echo "Building Vite assets..."
npm run build

echo "Running migrations..."
php artisan migrate --force

echo "Running seeders (if needed)..."
# php artisan db:seed --force

echo "Caching config and routes..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo "Creating storage link..."
php artisan storage:link

echo "Build complete!"
