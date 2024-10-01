<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run()
    {
        // Check if the user already exists to avoid duplication
        if (!User::where('email', 'admin@gmail.com')->exists()) {
            $admin = User::create([
                'name' => 'Admin User',
                'email' => 'admin@gmail.com',
                'password' => Hash::make('password'),
                'status' => 'active' // Assuming you have a 'status' column
            ]);

            // Assign the admin role to the user
            $admin->assignRole('admin');
        }
    }
}
