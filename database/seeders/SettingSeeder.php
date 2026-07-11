<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Laravix\Cms\Models\Setting;
use Laravix\Cms\Models\Site;

class SettingSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        Site::all()->each(function (Site $site) {
            Setting::factory()->for($site)->createMany([
                ['key' => 'site_name', 'value' => fake()->company()],
                ['key' => 'site_description', 'value' => fake()->sentence()],
                ['key' => 'contact_email', 'value' => fake()->email()],
                ['key' => 'contact_phone', 'value' => fake()->phoneNumber()],
                ['key' => 'facebook_url', 'value' => 'https://facebook.com/'.fake()->slug()],
                ['key' => 'twitter_url', 'value' => 'https://twitter.com/'.fake()->slug()],
                ['key' => 'instagram_url', 'value' => 'https://instagram.com/'.fake()->slug()],
                ['key' => 'footer_text', 'value' => fake()->sentence()],
            ]);
        });
    }
}
