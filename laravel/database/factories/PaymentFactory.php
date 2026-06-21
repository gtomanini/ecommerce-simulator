<?php

namespace Database\Factories;

use App\Models\Order;
use App\Models\Payment;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Payment>
 */
class PaymentFactory extends Factory
{
    protected $model = Payment::class;

    public function definition(): array
    {
        return [
            'order_id' => Order::factory(),
            'method' => fake()->randomElement(['credit_card', 'debit_card', 'pix']),
            'amount' => fake()->randomFloat(2, 50, 1000),
            'status' => 'completed',
            'transaction_id' => 'TXN-' . fake()->unique()->numerify('##############'),
            'payment_data' => null,
        ];
    }
}
