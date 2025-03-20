<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Call the RoleSeeder first
        $this->call([
            RoleSeeder::class,
        ]);

        // Create test user
        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'role_id' => 1, // This will assign the admin role to the test user
        ]);

        // You can also create additional test users with different roles if needed
        User::factory()->create([
            'name' => 'Inventory Manager',
            'email' => 'inventory@example.com',
            'role_id' => 2, // This will assign the inventory_manager role
        ]);

        User::factory()->create([
            'name' => 'Staff Member',
            'email' => 'staff@example.com',
            'role_id' => 3, // This will assign the staff role
        ]);
    }
}
