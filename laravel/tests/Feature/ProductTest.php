<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\ProductVariation;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_list_products(): void
    {
        Product::factory()->count(5)->create();

        $response = $this->getJson('/api/products');

        $response->assertStatus(200)
            ->assertJsonStructure(['data', 'current_page', 'total']);
        $this->assertCount(5, $response->json('data'));
    }

    public function test_can_filter_products_by_category(): void
    {
        $category = Category::factory()->create();
        Product::factory()->count(2)->create(['category_id' => $category->id]);
        Product::factory()->count(3)->create();

        $response = $this->getJson('/api/products?category_id=' . $category->id);

        $response->assertStatus(200);
        $this->assertCount(2, $response->json('data'));
    }

    public function test_can_search_products_by_name(): void
    {
        Product::factory()->create(['name' => 'Unique Gadget XYZ']);
        Product::factory()->count(3)->create();

        $response = $this->getJson('/api/products?search=Unique Gadget');

        $response->assertStatus(200);
        $this->assertGreaterThanOrEqual(1, count($response->json('data')));
    }

    public function test_can_sort_products_by_price_asc(): void
    {
        Product::factory()->create(['price' => 100, 'name' => 'B']);
        Product::factory()->create(['price' => 50, 'name' => 'A']);

        $response = $this->getJson('/api/products?sort=price_asc');

        $response->assertStatus(200);
        $prices = array_column($response->json('data'), 'price');
        $this->assertEquals($prices, collect($prices)->sort()->values()->all());
    }

    public function test_can_sort_products_by_price_desc(): void
    {
        Product::factory()->create(['price' => 100]);
        Product::factory()->create(['price' => 50]);

        $response = $this->getJson('/api/products?sort=price_desc');

        $response->assertStatus(200);
        $prices = array_column($response->json('data'), 'price');
        $this->assertEquals($prices, collect($prices)->sortDesc()->values()->all());
    }

    public function test_can_sort_products_by_newest(): void
    {
        Product::factory()->count(3)->create();

        $response = $this->getJson('/api/products?sort=newest');
        $response->assertStatus(200);
    }

    public function test_can_use_default_sort(): void
    {
        Product::factory()->count(3)->create();

        $response = $this->getJson('/api/products?sort=invalid_option');
        $response->assertStatus(200);
    }

    public function test_can_paginate_products_with_custom_per_page(): void
    {
        Product::factory()->count(20)->create();

        $response = $this->getJson('/api/products?per_page=5');

        $response->assertStatus(200);
        $this->assertCount(5, $response->json('data'));
    }

    public function test_can_show_single_product_with_relations(): void
    {
        $product = Product::factory()->create();
        ProductImage::factory()->create(['product_id' => $product->id]);
        ProductVariation::factory()->create(['product_id' => $product->id]);

        $response = $this->getJson('/api/products/' . $product->id);

        $response->assertStatus(200)
            ->assertJson(['id' => $product->id])
            ->assertJsonStructure(['id', 'name', 'category', 'images', 'variations']);
    }

    public function test_show_returns_404_for_missing_product(): void
    {
        $response = $this->getJson('/api/products/99999');
        $response->assertStatus(404);
    }
}
