#!/bin/bash
# File: railway-start.sh

echo "=== RAILWAY START SCRIPT ==="

# Setup storage (CRITICAL for Railway Volume)
echo "Setting up storage link..."
php artisan storage:link --force || true

# Ensure storage directory permissions (if needed)
chmod -R 775 storage bootstrap/cache 2>/dev/null || true

# Run migrations on startup
echo "Running database migrations..."
php artisan migrate --force

# Optional: Run seeders
# php artisan db:seed --force

# Start PHP-FPM (default Railway web server)
# Railway uses nginx + php-fpm automatically for PHP apps
# We just need to keep the process alive
echo "Starting application..."

# Use supervisord or just let Railway handle it
# Railway will automatically start PHP-FPM if we don't specify a custom command
# So we can just sleep infinity to keep container running
# OR better yet, let Railway use its default PHP start script

# Method 1: Let Railway handle everything (RECOMMENDED)
# Don't specify a start command at all in nixpacks.toml

# Method 2: Keep container alive (if needed)
# tail -f /dev/null