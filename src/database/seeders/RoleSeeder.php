<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('roles')->insert([
            [
                'name' => 'root',
                'description' => 'Superusuario com acesso total ao sistema',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'admin',
                'description' => 'Administrador com acesso gerencial',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'seller',
                'description' => 'Vendedor da loja com permissões limitadas',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'user',
                'description' => 'Usuario padrão do sistema',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
