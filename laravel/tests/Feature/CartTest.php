<?php

namespace Tests\Feature;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class CartTest extends TestCase
{
    use RefreshDatabase;

    public function test_cart_requires_authentication(): void
    {
        $this->getJson('/api/cart')->assertStatus(401);
    }

    public function test_index_returns_empty_cart_when_none_exists(): void
    {
        Sanctum::actingAs(User::factory()->create());

        $response = $this->getJson('/api/cart');

        $response->assertStatus(200)
            ->assertJson(['id' => null, 'items' => [], 'total' => 0]);
    }

    public function test_index_returns_cart_with_items_and_total(): void
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $cart = Cart::factory()->create(['user_id' => $user->id]);
        CartItem::factory()->create(['cart_id' => $cart->id, 'price' => 20, 'quantity' => 2]);

        $response = $this->getJson('/api/cart');

        $response->assertStatus(200)
            ->assertJson(['id' => $cart->id, 'total' => 40]);
        $this->assertCount(1, $response->json('items'));
    }

    public function test_can_add_item_to_cart(): void
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);
        $product = Product::factory()->create(['price' => 50]);

        $response = $this->postJson('/api/cart', [
            'product_id' => $product->id,
            'quantity' => 2,
        ]);

        $response->assertStatus(201);
        $this->assertDatabaseHas('cart_items', [
            'product_id' => $product->id,
            'quantity' => 2,
        ]);
        $this->assertDatabaseHas('audit_logs', ['action' => 'cart_item_added']);
    }

    public function test_adding_same_product_increments_quantity(): void
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);
        $product = Product::factory()->create(['price' => 50]);

        $this->postJson('/api/cart', ['product_id' => $product->id, 'quantity' => 2]);
        $this->postJson('/api/cart', ['product_id' => $product->id, 'quantity' => 3]);

        $cart = Cart::where('user_id', $user->id)->first();
        $this->assertDatabaseHas('cart_items', [
            'cart_id' => $cart->id,
            'product_id' => $product->id,
            'quantity' => 5,
        ]);
    }

    public function test_add_to_cart_validates_input(): void
    {
        Sanctum::actingAs(User::factory()->create());

        $response = $this->postJson('/api/cart', [
            'product_id' => 99999,
            'quantity' => 0,
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['product_id', 'quantity']);
    }

    public function test_can_update_cart_item_quantity(): void
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);
        $item = CartItem::factory()->create(['quantity' => 1]);

        $response = $this->putJson('/api/cart/' . $item->id, ['quantity' => 5]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('cart_items', ['id' => $item->id, 'quantity' => 5]);
    }

    public function test_updating_quantity_to_zero_removes_item(): void
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);
        $item = CartItem::factory()->create(['quantity' => 1]);

        $response = $this->putJson('/api/cart/' . $item->id, ['quantity' => 0]);

        $response->assertStatus(200)
            ->assertJson(['message' => 'Item removed from cart']);
        $this->assertDatabaseMissing('cart_items', ['id' => $item->id]);
    }

    public function test_can_remove_cart_item(): void
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);
        $item = CartItem::factory()->create();

        $response = $this->deleteJson('/api/cart/' . $item->id);

        $response->assertStatus(200)
            ->assertJson(['message' => 'Item removed from cart']);
        $this->assertDatabaseMissing('cart_items', ['id' => $item->id]);
        $this->assertDatabaseHas('audit_logs', ['action' => 'cart_item_removed']);
    }
}
