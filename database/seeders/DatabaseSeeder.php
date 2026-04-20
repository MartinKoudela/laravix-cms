<?php

namespace Database\Seeders;

use App\Enums\SiteRole;
use App\Models\Site;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {

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

        $this->call([
            SiteSeeder::class,
            UserSeeder::class,
            MediaSeeder::class,
            ContentSeeder::class,
            SettingSeeder::class,
            TaxonomySeeder::class,
        ]);

        Site::all()->each(function (Site $site) {
            User::all()->each(function (User $user) use ($site) {
                $site->users()->attach($user->id, [
                    'role' => fake()->randomElement(SiteRole::cases()),
                ]);
            });
        });
    }
}
