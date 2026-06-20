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
                'name' => '🎯 Primeira Compra',
                'description' => 'Parabéns! Você fez sua primeira compra',
                'icon' => '🎯',
                'condition' => 'first_purchase'
            ],
            [
                'name' => '💰 Gastador de Ouro',
                'description' => 'Parabéns! Você gastou R$ 100 em compras',
                'icon' => '💰',
                'condition' => 'spent_100'
            ],
            [
                'name' => '💎 Gastador de Diamante',
                'description' => 'Parabéns! Você gastou R$ 500 em compras',
                'icon' => '💎',
                'condition' => 'spent_500'
            ],
            [
                'name' => '👑 Colecionador',
                'description' => 'Parabéns! Você comprou produtos de 5 categorias diferentes',
                'icon' => '👑',
                'condition' => 'categories_5'
            ],
            [
                'name' => '🛍️ Chegou ao Limite',
                'description' => 'Uau! Você adicionou 10 ou mais itens ao carrinho',
                'icon' => '🛍️',
                'condition' => 'cart_items_10'
            ],
            [
                'name' => '⚡ Luz Rápida',
                'description' => 'Você completou uma compra em menos de 2 minutos',
                'icon' => '⚡',
                'condition' => 'quick_purchase'
            ],
            [
                'name' => '🌟 Estrela em Ascensão',
                'description' => 'Você desbloqueou 5 conquistas diferentes',
                'icon' => '🌟',
                'condition' => 'achievements_5'
            ],
            [
                'name' => '🎁 Presenteador',
                'description' => 'Você comprou para mais de uma pessoa',
                'icon' => '🎁',
                'condition' => 'multiple_buyers'
            ],
            [
                'name' => '📱 Madrugador',
                'description' => 'Você fez uma compra entre 00:00 e 06:00',
                'icon' => '📱',
                'condition' => 'midnight_shopping'
            ],
            [
                'name' => '🏆 Campeão de Vendas',
                'description' => 'Parabéns! Você completou 10 compras',
                'icon' => '🏆',
                'condition' => 'orders_10'
            ],
            [
                'name' => '🚀 Variedade é Vida',
                'description' => 'Você comprou produtos de todas as 9 categorias',
                'icon' => '🚀',
                'condition' => 'all_categories'
            ],
            [
                'name' => '💸 Milionário Fictício',
                'description' => 'Você gastou R$ 1000 em compras simuladas',
                'icon' => '💸',
                'condition' => 'spent_1000'
            ],
        ];

        foreach ($achievements as $achievement) {
            Achievement::create($achievement);
        }
    }
}
