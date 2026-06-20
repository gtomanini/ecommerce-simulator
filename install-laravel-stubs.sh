#!/bin/bash

cd /app

echo "📦 Installing Shopping Simulator Application..."

# Copy Models
echo "📋 Installing Models..."
cp /app/laravel-stubs/Models/*.php /app/app/Models/ 2>/dev/null || true

# Copy Migrations
echo "📋 Installing Migrations..."
MIGRATION_DIR="/app/database/migrations"
for file in /app/laravel-stubs/Migrations/*.php; do
    timestamp=$(date +%Y%m%d%H%M%S)
    filename=$(basename "$file")
    new_name="${timestamp}_${filename}"
    cp "$file" "$MIGRATION_DIR/$new_name"
    sleep 1
done

# Copy Seeders
echo "🌱 Installing Seeders..."
cp /app/laravel-stubs/Seeders/*.php /app/database/seeders/ 2>/dev/null || true

# Run migrations
echo "🚀 Running migrations..."
php artisan migrate:fresh --force

# Run seeders
echo "🌱 Seeding database..."
php artisan db:seed --class=CategorySeeder --force
php artisan db:seed --class=ShippingMethodSeeder --force
php artisan db:seed --class=AchievementSeeder --force
php artisan db:seed --class=ProductSeeder --force

echo "✅ Installation complete!"
