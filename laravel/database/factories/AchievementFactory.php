<?php

namespace Database\Factories;

use App\Models\Achievement;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Achievement>
 */
class AchievementFactory extends Factory
{
    protected $model = Achievement::class;

    public function definition(): array
    {
        return [
            'name' => fake()->unique()->words(2, true),
            'description' => fake()->sentence(),
            'icon' => fake()->randomElement(['🎯', '💰', '💎', '👑', '🏆']),
            'condition' => fake()->unique()->randomElement(['first_purchase', 'spent_100', 'spent_500', 'orders_10', 'categories_5']),
        ];
    }
}
