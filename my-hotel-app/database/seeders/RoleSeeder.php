<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;

class RoleSeeder extends Seeder
{
    public function run()
    {
        $roles = [
            'admin',
            'inventory_manager',
            'staff'
        ];

        foreach ($roles as $role) {
            Role::create(['name' => $role]);
        }
    }
}
