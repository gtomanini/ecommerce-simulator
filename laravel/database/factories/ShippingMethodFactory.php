<?php

namespace Database\Factories;

use App\Models\ShippingMethod;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ShippingMethod>
 */
class ShippingMethodFactory extends Factory
{
    protected $model = ShippingMethod::class;

    public function definition(): array
    {
        return [
            'name' => fake()->unique()->randomElement(['Standard', 'Express', 'Fast', 'Pickup']) . ' ' . fake()->numberBetween(1, 1000),
            'description' => fake()->sentence(),
            'base_cost' => fake()->randomFloat(2, 0, 60),
            'estimated_days' => fake()->numberBetween(1, 10),
        ];
    }
}
