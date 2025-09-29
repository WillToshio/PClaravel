<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Role;
use App\Models\Plan;

class UserSeeder extends Seeder
{
    public function run(): void
    {

        // Disable foreign key checks for better error handling


        $this->createRootUser();
        $this->createTestUsers();

    }

    private function createRootUser():void {
         // 3. Create the Root user
        $rootUser = User::firstOrCreate(
            ['email' => env('ROOT_EMAIL', 'root@example.com')],
            [
                'name' => 'Root User',
                'password' => Hash::make(env('ROOT_PASSWORD', 'root123')),
                'is_active' => true,
                'email_verified_at' => now(),
            ]
        );

        // 4. Assign root role and plan to Root user
        $rootUser->roles()->sync([$roleRoot->id]);
        $rootUser->plans()->sync([$planDeluxe->id => [
            'starts_at' => now(),
            'expires_at' => now()->addYear(),
            'is_active' => true
        ]]);

    }

    private function createTestUsers(): void
    {
        try {
            $userRole = Role::where('name', 'user')->first();
            $basicPlan = Plan::where('name', 'Free')->first();

            User::factory(10)->create()->each(function ($user) use ($userRole, $basicPlan) {
                if ($userRole) {
                    $user->role()->attach($userRole->id);
                }
                if ($basicPlan) {
                    $user->plan()->attach($basicPlan->id, [
                        'starts_at' => now(),
                        'expires_at' => now()->addMonth(),
                        'is_active' => true
                    ]);
                }
            });

            $this->command->info('- 10 usuários de teste criados com sucesso!');
        } catch (\Exception $e) {
            $this->command->error('x Erro nos usuários de teste: ' . $e->getMessage());
        }
    }
}
