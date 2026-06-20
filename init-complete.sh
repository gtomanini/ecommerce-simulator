#!/bin/bash

cd /app

echo "🚀 Initializing Shopping Simulator..."

# Wait for database to be ready
echo "⏳ Waiting for database..."
for i in {1..30}; do
    php artisan db:show && break || (echo "Attempt $i..."; sleep 2)
done

# Create models with migrations
echo "📦 Creating application structure..."

php artisan make:model Category -m --force 2>/dev/null || true
php artisan make:model Product -m --force 2>/dev/null || true
php artisan make:model ProductImage -m --force 2>/dev/null || true
php artisan make:model ProductVariation -m --force 2>/dev/null || true
php artisan make:model Cart -m --force 2>/dev/null || true
php artisan make:model CartItem -m --force 2>/dev/null || true
php artisan make:model Order -m --force 2>/dev/null || true
php artisan make:model OrderItem -m --force 2>/dev/null || true
php artisan make:model ShippingMethod -m --force 2>/dev/null || true
php artisan make:model Payment -m --force 2>/dev/null || true
php artisan make:model Achievement -m --force 2>/dev/null || true
php artisan make:model UserAchievement -m --force 2>/dev/null || true

# Create API Controllers
echo "🎮 Creating controllers..."
php artisan make:controller Api/ProductController --api --force 2>/dev/null || true
php artisan make:controller Api/CartController --api --force 2>/dev/null || true
php artisan make:controller Api/OrderController --api --force 2>/dev/null || true
php artisan make:controller Api/PaymentController --api --force 2>/dev/null || true
php artisan make:controller Api/AchievementController --api --force 2>/dev/null || true

# Create Seeders
echo "🌱 Creating seeders..."
php artisan make:seeder CategorySeeder --force 2>/dev/null || true
php artisan make:seeder ProductSeeder --force 2>/dev/null || true
php artisan make:seeder ShippingMethodSeeder --force 2>/dev/null || true
php artisan make:seeder AchievementSeeder --force 2>/dev/null || true

# Create seeders data file - will be sourced later
cat > /tmp/create_seeders.php << 'SEEDER_EOF'
// Created seeders will be populated by this script
SEEDER_EOF

echo "✅ Application structure created!"
echo "📋 Next steps:"
echo "   1. Update migrations files"
echo "   2. Update seeders"
echo "   3. Run: php artisan migrate:fresh --seed"
