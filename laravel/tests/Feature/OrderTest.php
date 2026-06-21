<?php

namespace Tests\Feature;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Order;
use App\Models\Product;
use App\Models\ShippingMethod;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class OrderTest extends TestCase
{
    use RefreshDatabase;

    private function validOrderPayload(int $shippingMethodId): array
    {
        return [
            'shipping_method_id' => $shippingMethodId,
            'buyer_name' => 'John Doe',
            'buyer_email' => 'john@example.com',
            'buyer_phone' => '11999999999',
            'delivery_address' => 'Main Street 123',
            'delivery_city' => 'Sao Paulo',
            'delivery_state' => 'SP',
            'delivery_zip' => '01310100',
        ];
    }

    public function test_orders_require_authentication(): void
    {
        $this->getJson('/api/orders')->assertStatus(401);
    }

    public function test_can_list_user_orders(): void
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);
        Order::factory()->count(2)->create(['user_id' => $user->id]);
        Order::factory()->create(); // another user's order

        $response = $this->getJson('/api/orders');

        $response->assertStatus(200)
            ->assertJsonStructure(['data', 'current_page']);
        $this->assertCount(2, $response->json('data'));
    }

    public function test_can_show_own_order(): void
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);
        $order = Order::factory()->create(['user_id' => $user->id]);

        $response = $this->getJson('/api/orders/' . $order->id);

        $response->assertStatus(200)
            ->assertJson(['id' => $order->id]);
    }

    public function test_cannot_show_other_users_order(): void
    {
        Sanctum::actingAs(User::factory()->create());
        $order = Order::factory()->create();

        $response = $this->getJson('/api/orders/' . $order->id);

        $response->assertStatus(404);
    }

    public function test_can_create_order_from_cart(): void
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $shipping = ShippingMethod::factory()->create(['base_cost' => 15, 'estimated_days' => 5]);
        $cart = Cart::factory()->create(['user_id' => $user->id]);
        $product = Product::factory()->create();
        CartItem::factory()->create([
            'cart_id' => $cart->id,
            'product_id' => $product->id,
            'price' => 100,
            'quantity' => 2,
        ]);

        $response = $this->postJson('/api/orders', $this->validOrderPayload($shipping->id));

        $response->assertStatus(201)
            ->assertJsonStructure(['id', 'order_number', 'total', 'items', 'shipping_method']);

        // subtotal 200 + shipping 15 = 215
        $this->assertEquals(215, $response->json('total'));
        $this->assertDatabaseHas('orders', ['user_id' => $user->id, 'total' => 215]);
        $this->assertDatabaseHas('payments', ['status' => 'completed']);
        $this->assertDatabaseHas('audit_logs', ['action' => 'order_created']);
        // cart cleared
        $this->assertDatabaseMissing('cart_items', ['cart_id' => $cart->id]);
    }

    public function test_cannot_create_order_with_empty_cart(): void
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);
        $shipping = ShippingMethod::factory()->create();

        $response = $this->postJson('/api/orders', $this->validOrderPayload($shipping->id));

        $response->assertStatus(400)
            ->assertJson(['message' => 'Cart is empty']);
    }

    public function test_create_order_validates_input(): void
    {
        Sanctum::actingAs(User::factory()->create());

        $response = $this->postJson('/api/orders', []);

        $response->assertStatus(422)
            ->assertJsonValidationErrors([
                'shipping_method_id',
                'buyer_name',
                'buyer_email',
                'buyer_phone',
                'delivery_address',
                'delivery_city',
                'delivery_state',
                'delivery_zip',
            ]);
    }
}
