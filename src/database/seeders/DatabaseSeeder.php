<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Role;
use App\Models\Plan;
use App\Models\Module;
use App\Models\Permission;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Create essential roles first
        $roleRoot = Role::firstOrCreate(['name' => 'root'], [
            'description' => 'Super administrator with full system access'
        ]);

        $roleAdmin = Role::firstOrCreate(['name' => 'admin'], [
            'description' => 'System administrator'
        ]);
        $roleSeller = Role::firstOrCreate(['name' => 'seller'], [
            'description' => 'Regular seller'
        ]);
        $roleUser = Role::firstOrCreate(['name' => 'user'], [
            'description' => 'Regular user'
        ]);

        // 2. Create plans
        $planDeluxe = Plan::firstOrCreate(['name' => 'Deluxe'], [
            'price' => 99.99,
            'duration_days' => 365,
            'features' => 'All features included'
        ]);
        $planDeluxe = Plan::firstOrCreate(['name' => 'Premium'], [
            'price' => 59.99,
            'duration_days' => 180,
            'features' => 'All features included'
        ]);
        $planDeluxe = Plan::firstOrCreate(['name' => 'Basic'], [
            'price' => 29.99,
            'duration_days' => 90,
            'features' => 'All features included'
        ]);
        $planFree = Plan::firstOrCreate(['name' => 'Free'], [
            'price' => 0,
            'duration_days' => 30,
            'features' => 'Basic features'
        ]);

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

        // 5. Create 10 random users with roles and plans
        $regularUsers = User::factory(10)->create();

        foreach ($regularUsers as $user) {
            // Assign user role
            $user->roles()->sync([$roleUser->id]);

            // Assign free plan (50% chance) or random plan
            $randomPlan = fake()->boolean(50) ? $planFree : Plan::inRandomOrder()->first();
            $user->plans()->sync([$randomPlan->id => [
                'starts_at' => now(),
                'expires_at' => now()->addDays($randomPlan->duration_days),
                'is_active' => true
            ]]);
        }
        /*
        // 6. Optional: Create modules and permissions
        $modules = ['dashboard', 'reports', 'billing', 'users', 'settings'];

        foreach ($modules as $moduleKey) {
            $module = Module::firstOrCreate(['key' => $moduleKey], [
                'name' => ucfirst($moduleKey),
                'description' => "Access to {$moduleKey} functionality"
            ]);

            // Grant full permissions to root role for all modules
            Permission::firstOrCreate([
                'module_id' => $module->id,
                'role_id' => $roleRoot->id
            ], [
                'can_read' => true,
                'can_write' => true,
                'can_delete' => true,
                'requires_approval' => false
            ]);
        }
        */
    }
}
