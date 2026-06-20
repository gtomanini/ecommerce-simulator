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
                'name' => 'Entrega Econômica',
                'description' => 'Entrega padrão em 7-10 dias úteis',
                'base_cost' => 15.00,
                'estimated_days' => 10
            ],
            [
                'name' => 'Entrega Rápida',
                'description' => 'Entrega em 3-5 dias úteis',
                'base_cost' => 35.00,
                'estimated_days' => 5
            ],
            [
                'name' => 'Entrega Express',
                'description' => 'Entrega em 1-2 dias úteis',
                'base_cost' => 59.90,
                'estimated_days' => 2
            ],
            [
                'name' => 'Retirada na Loja',
                'description' => 'Retire seu pedido na loja (São Paulo)',
                'base_cost' => 0.00,
                'estimated_days' => 1
            ],
        ];

        foreach ($methods as $method) {
            ShippingMethod::create($method);
        }
    }
}
