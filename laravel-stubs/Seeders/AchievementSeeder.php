<?php

namespace Database\Seeders;

use App\Models\Achievement;
use Illuminate\Database\Seeder;

class AchievementSeeder extends Seeder
{
    public function run(): void
    {
        $achievements = [
            [
                'name' => '🎯 First Purchase',
                'description' => 'Congratulations! You made your first purchase',
                'icon' => '🎯',
                'condition' => 'first_purchase'
            ],
            [
                'name' => '💰 Gold Spender',
                'description' => 'Congratulations! You spent $100 on purchases',
                'icon' => '💰',
                'condition' => 'spent_100'
            ],
            [
                'name' => '💎 Diamond Spender',
                'description' => 'Congratulations! You spent $500 on purchases',
                'icon' => '💎',
                'condition' => 'spent_500'
            ],
            [
                'name' => '👑 Collector',
                'description' => 'Congratulations! You bought products from 5 different categories',
                'icon' => '👑',
                'condition' => 'categories_5'
            ],
            [
                'name' => '🛍️ Cart Limit Reached',
                'description' => 'Wow! You added 10 or more items to your cart',
                'icon' => '🛍️',
                'condition' => 'cart_items_10'
            ],
            [
                'name' => '⚡ Lightning Fast',
                'description' => 'You completed a purchase in less than 2 minutes',
                'icon' => '⚡',
                'condition' => 'quick_purchase'
            ],
            [
                'name' => '🌟 Rising Star',
                'description' => 'You unlocked 5 different achievements',
                'icon' => '🌟',
                'condition' => 'achievements_5'
            ],
            [
                'name' => '🎁 Gift Giver',
                'description' => 'You made purchases for more than one person',
                'icon' => '🎁',
                'condition' => 'multiple_buyers'
            ],
            [
                'name' => '📱 Night Owl',
                'description' => 'You made a purchase between 00:00 and 06:00',
                'icon' => '📱',
                'condition' => 'midnight_shopping'
            ],
            [
                'name' => '🏆 Sales Champion',
                'description' => 'Congratulations! You completed 10 purchases',
                'icon' => '🏆',
                'condition' => 'orders_10'
            ],
            [
                'name' => '🚀 Variety is Life',
                'description' => 'You bought products from all 9 categories',
                'icon' => '🚀',
                'condition' => 'all_categories'
            ],
            [
                'name' => '💸 Fictional Millionaire',
                'description' => 'You spent $1000 on simulated purchases',
                'icon' => '💸',
                'condition' => 'spent_1000'
            ],
        ];

        foreach ($achievements as $achievement) {
            Achievement::create($achievement);
        }
    }
}
