<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = User::create([
            'name' => 'Admin',
            'last_name' => 'Master',
            'email' => 'admin@masterbuilder.com',
            'password' => Hash::make('password'),
        ]);
        $admin->assignRole('admin');

        $user = User::create([
            'name' => 'John',
            'last_name' => 'Doe',
            'email' => 'john@example.com',
            'password' => Hash::make('password'),
        ]);
        $user->assignRole('user');
    }
}
