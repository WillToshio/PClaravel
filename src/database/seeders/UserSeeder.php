<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;
use App\Models\Plan;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $roleRoot = Role::where('name', 'root')->first();
        $planDeluxe = Plan::where('name', 'Deluxe')->first();

        // Usuário fixo root
        User::firstOrCreate(
            ['email' => env('ROOT_EMAIL', 'root@example.com')],
            [
            'name' => 'Root User',
            'password' => bcrypt(env('ROOT_PASSWORD', 'root123')),
            'role_id' => $roleRoot->id,
            'plan_id' => $planDeluxe->id,
        ]);

        // Usuários de teste
        User::factory(10)->create();
    }
}
