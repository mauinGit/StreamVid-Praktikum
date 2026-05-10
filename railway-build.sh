#!/bin/bash
# File: railway-build.sh

echo "=== RAILWAY BUILD SCRIPT ==="
echo "Installing composer dependencies..."
composer install --optimize-autoloader --no-dev

echo "Installing Node.js dependencies..."
npm ci

echo "Building Vite assets..."
npm run build

echo "Caching Laravel..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo "✓ Build completed"