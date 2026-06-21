<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\ShippingMethod;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CategoryAndShippingTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_list_categories(): void
    {
        Category::factory()->count(4)->create();

        $response = $this->getJson('/api/categories');

        $response->assertStatus(200);
        $this->assertCount(4, $response->json());
    }

    public function test_categories_list_empty_when_none(): void
    {
        $response = $this->getJson('/api/categories');

        $response->assertStatus(200)
            ->assertJson([]);
    }

    public function test_can_list_shipping_methods(): void
    {
        ShippingMethod::factory()->count(3)->create();

        $response = $this->getJson('/api/shipping-methods');

        $response->assertStatus(200);
        $this->assertCount(3, $response->json());
    }
}
