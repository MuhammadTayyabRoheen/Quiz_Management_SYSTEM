<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // Call the RolePermissionSeeder instead of RolesTableSeeder
        $this->call(RolePermissionSeeder::class);

        // Optionally call other seeders
        $this->call(AdminSeeder::class);
    }
}
