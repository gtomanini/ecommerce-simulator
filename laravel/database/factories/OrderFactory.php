<?php

namespace Database\Factories;

use App\Models\Order;
use App\Models\ShippingMethod;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Order>
 */
class OrderFactory extends Factory
{
    protected $model = Order::class;

    public function definition(): array
    {
        $subtotal = fake()->randomFloat(2, 50, 1000);
        $shippingCost = fake()->randomFloat(2, 0, 60);

        return [
            'user_id' => User::factory(),
            'order_number' => 'ORD-' . fake()->unique()->numerify('##############'),
            'subtotal' => $subtotal,
            'shipping_cost' => $shippingCost,
            'total' => $subtotal + $shippingCost,
            'status' => 'pending',
            'buyer_name' => fake()->name(),
            'buyer_email' => fake()->safeEmail(),
            'buyer_phone' => fake()->phoneNumber(),
            'delivery_address' => fake()->streetAddress(),
            'delivery_city' => fake()->city(),
            'delivery_state' => fake()->stateAbbr(),
            'delivery_zip' => fake()->postcode(),
            'shipping_method_id' => ShippingMethod::factory(),
            'estimated_delivery' => now()->addDays(5),
        ];
    }
}
