<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PlanSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('plans')->insert([
            [
                'name' => 'Free',
                'price' => 0,
                'duration_days' => null,
                'features' => json_encode(['Acesso básico', 'Limite reduzido de funcionalidades']),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Basic',
                'price' => 29.90,
                'duration_days' => 30,
                'features' => json_encode(['Funcionalidades essenciais', 'Suporte básico']),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Premium',
                'price' => 59.90,
                'duration_days' => 30,
                'features' => json_encode(['Funcionalidades avançadas', 'Suporte prioritário']),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Deluxe',
                'price' => 199.90,
                'duration_days' => 365,
                'features' => json_encode(['Tudo ilimitado', 'Suporte VIP']),
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
