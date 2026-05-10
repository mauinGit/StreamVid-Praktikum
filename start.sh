#!/bin/bash
set -e

echo "=== Starting Laravel on Railway ==="

# 1. Pastikan direktori storage ada (penting untuk Volume pertama kali)
mkdir -p /app/storage/app/public
mkdir -p /app/storage/app/private
mkdir -p /app/storage/framework/cache
mkdir -p /app/storage/framework/sessions
mkdir -p /app/storage/framework/views
mkdir -p /app/storage/logs

# Set permission
chmod -R 775 /app/storage
chown -R www-data:www-data /app/storage 2>/dev/null || true

# 2. Buat/perbarui symlink storage
echo "Creating storage symlink..."
php artisan storage:link --force

# Verifikasi symlink
if [ -L /app/public/storage ]; then
    echo "✓ Symlink created: $(readlink /app/public/storage)"
else
    echo "✗ WARNING: Symlink failed! Trying manual creation..."
    ln -sfn /app/storage/app/public /app/public/storage
fi

# 3. Cache config untuk performa (opsional tapi direkomendasikan)
php artisan config:cache
php artisan route:cache

# 4. Substitusi PORT di nginx config
envsubst '$PORT' < /app/nginx.conf > /etc/nginx/conf.d/default.conf

# 5. Start PHP-FPM
echo "Starting PHP-FPM..."
php-fpm -D

# 6. Start Nginx (foreground)
echo "Starting Nginx on port $PORT..."
nginx -g 'daemon off;'