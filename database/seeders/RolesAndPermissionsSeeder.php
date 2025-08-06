<?php

namespace Database\Seeders;

use Spatie\Permission\Models\Role;
use Illuminate\Database\Seeder;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        // Clear the cache
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create roles
        Role::firstOrCreate(['name' => 'buyer', 'guard_name' => 'jwt']);
        Role::firstOrCreate(['name' => 'seller', 'guard_name' => 'jwt']);
        Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'jwt']);
    }
}
