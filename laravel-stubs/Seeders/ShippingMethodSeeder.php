<?php

namespace Database\Seeders;

use App\Models\ShippingMethod;
use Illuminate\Database\Seeder;

class ShippingMethodSeeder extends Seeder
{
    public function run(): void
    {
        $methods = [
            [
                'name' => 'Standard Shipping',
                'description' => 'Standard delivery in 7-10 business days',
                'base_cost' => 15.00,
                'estimated_days' => 10
            ],
            [
                'name' => 'Fast Shipping',
                'description' => 'Delivery in 3-5 business days',
                'base_cost' => 35.00,
                'estimated_days' => 5
            ],
            [
                'name' => 'Express Shipping',
                'description' => 'Delivery in 1-2 business days',
                'base_cost' => 59.90,
                'estimated_days' => 2
            ],
            [
                'name' => 'Store Pickup',
                'description' => 'Pick up your order at the store (São Paulo)',
                'base_cost' => 0.00,
                'estimated_days' => 1
            ],
        ];

        foreach ($methods as $method) {
            ShippingMethod::create($method);
        }
    }
}
