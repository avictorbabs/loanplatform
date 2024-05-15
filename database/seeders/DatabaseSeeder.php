<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;
use Illuminate\Support\Str;


class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RolesAndPermissionsSeeder::class,
            SetupAdminSeeder::class,
            // ... Add any other seeders you have here
        ]);
    }

    // public function run(): void
    // {
    //     $user = User::factory()->create([
    //         'id' => Str::uuid()->toString(),
    //         'name' => 'SuperAdmin2',
    //         'email' => 'admin3@hivenetwork.africa',
    //         'user_type' => 'admin',
    //         'password' => bcrypt('securepassword'), // Example password
    //         'email_verified_at' => now(),
    //         'primary_phone_number' => '08132888982',
    //         'agreed_to_terms' => true,
    //     ]);

    //     $role = Role::create([
    //         'id' => Str::uuid()->toString(),
    //         'name' => 'admin',
    //         'guard_name' => 'web'
    //     ]);

    //     if ($role) {
    //         logger('Role created successfully', ['role_id' => $role->id]);
    //     } else {
    //         logger('Failed to create role');
    //     }

    //     // Using firstOrFail to ensure the role exists or it will throw an exception
    //     $role = Role::where('name', 'admin')->firstOrFail();
    //     $user->assignRole($role);
    // }

}
