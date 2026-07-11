<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Laravix\Cms\Enums\SiteRole;
use Laravix\Cms\Models\Site;
use Laravix\Cms\Models\User;

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
            ContentSeeder::class,
            SettingSeeder::class,
            TaxonomySeeder::class,
        ]);

        // TODO: asi může zůstat, ale i tak není vhodné to mít takto, lepší je ->for() nebo ->has() a podobně jako alternativa této vazby
        Site::all()->each(function (Site $site) {
            User::all()->each(function (User $user) use ($site) {
                $site->users()->attach($user->id, [
                    'role' => fake()->randomElement(SiteRole::cases()),
                ]);
            });
        });
    }
}
