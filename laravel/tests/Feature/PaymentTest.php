<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class PaymentTest extends TestCase
{
    use RefreshDatabase;

    private function cardPayload(): array
    {
        return [
            'method' => 'credit_card',
            'card_holder' => 'John Doe',
            'card_number' => '4111111111111111',
            'card_expiry' => '12/30',
            'card_cvv' => '123',
        ];
    }

    public function test_payment_requires_authentication(): void
    {
        $order = Order::factory()->create(['status' => 'pending']);
        $this->postJson("/api/orders/{$order->id}/payment", $this->cardPayload())
            ->assertStatus(401);
    }

    public function test_can_pay_order_with_credit_card(): void
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);
        $order = Order::factory()->create(['user_id' => $user->id, 'status' => 'pending', 'total' => 215]);

        $response = $this->postJson("/api/orders/{$order->id}/payment", $this->cardPayload());

        $response->assertStatus(200)
            ->assertJson(['id' => $order->id, 'status' => 'confirmed']);

        $this->assertDatabaseHas('payments', [
            'order_id' => $order->id,
            'method' => 'credit_card',
            'status' => 'completed',
            'amount' => 215,
        ]);
        $this->assertDatabaseHas('orders', ['id' => $order->id, 'status' => 'confirmed']);
        $this->assertDatabaseHas('audit_logs', ['action' => 'payment_completed']);

        // The full card number/CVV must never be stored.
        $payment = Payment::where('order_id', $order->id)->first();
        $this->assertEquals('1111', $payment->payment_data['card_last4']);
        $this->assertArrayNotHasKey('card_cvv', $payment->payment_data);
    }

    public function test_can_pay_order_with_pix(): void
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);
        $order = Order::factory()->create(['user_id' => $user->id, 'status' => 'pending']);

        $response = $this->postJson("/api/orders/{$order->id}/payment", ['method' => 'pix']);

        $response->assertStatus(200)
            ->assertJson(['status' => 'confirmed']);

        $payment = Payment::where('order_id', $order->id)->first();
        $this->assertEquals('pix', $payment->method);
        $this->assertArrayHasKey('pix_key', $payment->payment_data);
    }

    public function test_card_payment_validates_card_fields(): void
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);
        $order = Order::factory()->create(['user_id' => $user->id, 'status' => 'pending']);

        $response = $this->postJson("/api/orders/{$order->id}/payment", [
            'method' => 'credit_card',
            'card_number' => 'not-a-number',
            'card_cvv' => '1',
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['card_holder', 'card_number', 'card_expiry', 'card_cvv']);
    }

    public function test_payment_requires_valid_method(): void
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);
        $order = Order::factory()->create(['user_id' => $user->id, 'status' => 'pending']);

        $this->postJson("/api/orders/{$order->id}/payment", ['method' => 'bitcoin'])
            ->assertStatus(422)
            ->assertJsonValidationErrors(['method']);
    }

    public function test_cannot_pay_another_users_order(): void
    {
        Sanctum::actingAs(User::factory()->create());
        $order = Order::factory()->create(['status' => 'pending']);

        $this->postJson("/api/orders/{$order->id}/payment", ['method' => 'pix'])
            ->assertStatus(404);
    }

    public function test_cannot_pay_already_paid_order(): void
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);
        $order = Order::factory()->create(['user_id' => $user->id, 'status' => 'confirmed']);
        Payment::factory()->create(['order_id' => $order->id, 'status' => 'completed']);

        $this->postJson("/api/orders/{$order->id}/payment", ['method' => 'pix'])
            ->assertStatus(422)
            ->assertJson(['message' => 'This order has already been paid']);
    }
}
