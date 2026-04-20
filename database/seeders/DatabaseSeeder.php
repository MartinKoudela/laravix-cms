<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            SiteSeeder::class,
            UserSeeder::class,
            MediaSeeder::class,
            ContentSeeder::class,
            SettingSeeder::class,
            TaxonomySeeder::class,
        ]);

        User::factory()->create([
            'name' => 'Martin Koudela',
            'email' => 'mk@martinkoudela.com',
            'password' => 'password',
            'is_super_admin' => true,
        ]);

        User::factory()->create([
            'name' => 'Jay Jay',
            'email' => 'hello@jakubforman.eu',
            'password' => 'password',
            'is_super_admin' => true,
        ]);
    }
}
