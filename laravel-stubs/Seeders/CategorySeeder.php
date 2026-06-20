<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Clothing',
                'slug' => 'clothing',
                'description' => 'T-shirts, pants, dresses, jackets and much more',
                'image_url' => 'https://images.unsplash.com/photo-1521572163474-6864f9cf17ab?w=400&h=300&fit=crop'
            ],
            [
                'name' => 'Electronics',
                'slug' => 'electronics',
                'description' => 'Smartphones, tablets, headphones and tech accessories',
                'image_url' => 'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?w=400&h=300&fit=crop'
            ],
            [
                'name' => 'Home Decor',
                'slug' => 'home-decor',
                'description' => 'Pictures, vases, lamps and home decoration items',
                'image_url' => 'https://images.unsplash.com/photo-1578500494198-246f612d03b3?w=400&h=300&fit=crop'
            ],
            [
                'name' => 'Shoes',
                'slug' => 'shoes',
                'description' => 'Sneakers, dress shoes, sandals and flip-flops',
                'image_url' => 'https://images.unsplash.com/photo-1542291026-7eec264c27ff?w=400&h=300&fit=crop'
            ],
            [
                'name' => 'Books',
                'slug' => 'books',
                'description' => 'Fiction, non-fiction, romance, mystery and much more',
                'image_url' => 'https://images.unsplash.com/photo-1524995997946-a1c2e315a42f?w=400&h=300&fit=crop'
            ],
            [
                'name' => 'Sports',
                'slug' => 'sports',
                'description' => 'Equipment for fitness, yoga, running and other sports',
                'image_url' => 'https://images.unsplash.com/photo-1534438327276-14e5300c3a48?w=400&h=300&fit=crop'
            ],
            [
                'name' => 'Beauty & Care',
                'slug' => 'beauty-care',
                'description' => 'Cosmetics, shampoos, creams and personal hygiene products',
                'image_url' => 'https://images.unsplash.com/photo-1556228578-8c89e6adf883?w=400&h=300&fit=crop'
            ],
            [
                'name' => 'Kitchen',
                'slug' => 'kitchen',
                'description' => 'Kitchen utensils, pans, frying pans and appliances',
                'image_url' => 'https://images.unsplash.com/photo-1556909114-f6e7ad7d3136?w=400&h=300&fit=crop'
            ],
            [
                'name' => 'Accessories',
                'slug' => 'accessories',
                'description' => 'Bags, belts, watches, glasses and other accessories',
                'image_url' => 'https://images.unsplash.com/photo-1548036328-c9fa89d128fa?w=400&h=300&fit=crop'
            ],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}
