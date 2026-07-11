<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Laravix\Cms\Models\User;

class UserSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {

        User::factory()->create([
            'name' => 'SuperAdmin',
            'email' => 'admin@example.com',
            'password' => Hash::make('example_'),
            'is_super_admin' => true,
        ]);

        User::factory()->create([
            'name' => 'Manager',
            'email' => 'manager@example.com',
            'password' => Hash::make('example_'),
            'is_super_admin' => false,
        ]);

        User::factory()->create([
            'name' => 'User',
            'email' => 'user@example.com',
            'password' => Hash::make('example_'),
            'is_super_admin' => false,
        ]);

        User::factory()->count(10)->create();
    }
}
