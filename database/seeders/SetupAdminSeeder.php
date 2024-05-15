<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;

class SetupAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $adminEmail = 'infotech@proconnectpay.com';

        // Check if the admin user already exists
        if (!User::where('email', $adminEmail)->exists()) {
            // Create the admin user
            $user = User::create([
                'name' => 'AdminV',
                'email' => $adminEmail,
                'password' => bcrypt('Z&)TvV8ojsGd'), // Example password
                'email_verified_at' => now(),
                // Add other necessary fields
            ]);

            $role = Role::where(['name' => 'admin'])->first();
            $user->assignRole($role);

            $this->command->info('Admin user created successfully.');
        } else {
            $this->command->info('Admin user already exists.');
        }

    }
}
