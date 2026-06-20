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
                'name' => 'Roupas',
                'slug' => 'roupas',
                'description' => 'Camisetas, calças, vestidos, jaquetas e muito mais',
                'image_url' => 'https://images.unsplash.com/photo-1521572163474-6864f9cf17ab?w=400&h=300&fit=crop'
            ],
            [
                'name' => 'Eletrônicos',
                'slug' => 'eletronicos',
                'description' => 'Smartphones, tablets, fones de ouvido e acessórios tech',
                'image_url' => 'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?w=400&h=300&fit=crop'
            ],
            [
                'name' => 'Decoração',
                'slug' => 'decoracao',
                'description' => 'Quadros, vasos, luminárias e itens de decoração para sua casa',
                'image_url' => 'https://images.unsplash.com/photo-1578500494198-246f612d03b3?w=400&h=300&fit=crop'
            ],
            [
                'name' => 'Sapatos',
                'slug' => 'sapatos',
                'description' => 'Tênis, sapatos sociais, sandálias e chinelos',
                'image_url' => 'https://images.unsplash.com/photo-1542291026-7eec264c27ff?w=400&h=300&fit=crop'
            ],
            [
                'name' => 'Livros',
                'slug' => 'livros',
                'description' => 'Ficção, não-ficção, romance, mistério e muito mais',
                'image_url' => 'https://images.unsplash.com/photo-1524995997946-a1c2e315a42f?w=400&h=300&fit=crop'
            ],
            [
                'name' => 'Esportes',
                'slug' => 'esportes',
                'description' => 'Equipamentos para fitness, yoga, corrida e outros esportes',
                'image_url' => 'https://images.unsplash.com/photo-1534438327276-14e5300c3a48?w=400&h=300&fit=crop'
            ],
            [
                'name' => 'Beleza & Cuidados',
                'slug' => 'beleza-cuidados',
                'description' => 'Cosméticos, shampoos, cremes e produtos de higiene pessoal',
                'image_url' => 'https://images.unsplash.com/photo-1556228578-8c89e6adf883?w=400&h=300&fit=crop'
            ],
            [
                'name' => 'Cozinha',
                'slug' => 'cozinha',
                'description' => 'Utensílios de cozinha, panelas, frigideiras e eletrodomésticos',
                'image_url' => 'https://images.unsplash.com/photo-1556909114-f6e7ad7d3136?w=400&h=300&fit=crop'
            ],
            [
                'name' => 'Acessórios',
                'slug' => 'acessorios',
                'description' => 'Bolsas, cintos, relógios, óculos e outros acessórios',
                'image_url' => 'https://images.unsplash.com/photo-1548036328-c9fa89d128fa?w=400&h=300&fit=crop'
            ],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}
