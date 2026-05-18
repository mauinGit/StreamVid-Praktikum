#!/bin/bash
set -e

echo "=== Starting Laravel on Railway ==="

# 1. Tunggu sebentar agar Volume Railway benar-benar ter-mount
sleep 2

# 2. Pastikan direktori storage ada (penting untuk Volume pertama kali)
mkdir -p /app/storage/app/public
mkdir -p /app/storage/app/private
mkdir -p /app/storage/framework/cache
mkdir -p /app/storage/framework/sessions
mkdir -p /app/storage/framework/views
mkdir -p /app/storage/logs

# Set permission
chmod -R 775 /app/storage
chown -R www-data:www-data /app/storage 2>/dev/null || true

# 3. Cache config untuk performa (SEBELUM symlink)
php artisan config:cache

# 4. Buat/perbarui symlink storage (SETELAH config:cache)
echo "Creating storage symlink..."

# Hapus dulu apapun yang ada di public/storage (folder biasa atau symlink lama)
rm -rf /app/public/storage

# Buat symlink manual langsung (lebih reliable daripada artisan di Railway)
ln -sfn /app/storage/app/public /app/public/storage

# Verifikasi symlink
if [ -L /app/public/storage ]; then
    echo "✓ Symlink OK: $(readlink /app/public/storage)"
    echo "✓ Isi storage: $(ls /app/storage/app/public 2>/dev/null || echo 'kosong')"
else
    echo "✗ WARNING: Symlink gagal dibuat, mencoba ulang..."
    ln -sfn /app/storage/app/public /app/public/storage
fi

# 5. Jalankan migrasi database
php artisan migrate --force || true

# 6. Substitusi PORT di nginx config
envsubst '$PORT' < /app/nginx.conf > /etc/nginx/conf.d/default.conf

# 7. Start PHP-FPM
echo "Starting PHP-FPM..."
php-fpm -D

# 8. Start Nginx (foreground)
echo "Starting Nginx on port $PORT..."
nginx -g 'daemon off;'