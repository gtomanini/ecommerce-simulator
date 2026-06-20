#!/bin/bash

cd /app

echo "🚀 Setting up Shopping Simulator application..."

# Create Models
echo "📦 Creating models..."
php artisan make:model Category -m
php artisan make:model Product -m
php artisan make:model ProductImage -m
php artisan make:model ProductVariation -m
php artisan make:model Cart -m
php artisan make:model CartItem -m
php artisan make:model Order -m
php artisan make:model OrderItem -m
php artisan make:model ShippingMethod -m
php artisan make:model Payment -m
php artisan make:model Achievement -m
php artisan make:model UserAchievement -m

# Create Controllers
echo "🎮 Creating controllers..."
php artisan make:controller ProductController --api
php artisan make:controller CartController --api
php artisan make:controller OrderController --api
php artisan make:controller PaymentController --api
php artisan make:controller AchievementController --api

# Create Seeders
echo "🌱 Creating seeders..."
php artisan make:seeder CategorySeeder
php artisan make:seeder ProductSeeder
php artisan make:seeder ShippingMethodSeeder
php artisan make:seeder AchievementSeeder

echo "✅ Scaffold created! Now edit migrations and seeders."
