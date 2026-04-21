<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        // TODO: tyto uživatele klidne smazat, nahradit je admin@, user@, manager@... vždy ...@example.com
        //  heslo mít jako example_ (splňuje podmínky + jednoduše zapamtovatelné) - heslo stejné pro všechny

        User::factory()->create([
            'name' => 'Martin Koudela',
            'email' => 'mk@martinkoudela.com',
            'password' => Hash::make('password'),
            'is_super_admin' => true,
        ]);

        User::factory()->create([
            'name' => 'Jay Jay',
            'email' => 'hello@jakubforman.eu',
            'password' => Hash::make('password'),
            'is_super_admin' => true,
        ]);

        User::factory()->create([
            'name' => 'Tomáš Vylímec',
            'email' => 'tomas.vylimec@gmail.com',
            'password' => Hash::make('password'),
            'is_super_admin' => true,
        ]);

        User::factory()->count(50)->create();
    }
}
