<?php

namespace Database\Seeders;

use App\Models\Site;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        // TODO: popřemíšlet jestli to nedat přímo do sites seederu opět přes ->for() nebo alternativu seederu
        Site::all()->each(function (Site $site) {
            $settings = [
                'site_name' => fake()->company(),
                'site_description' => fake()->sentence(),
                'contact_email' => fake()->email(),
                'contact_phone' => fake()->phoneNumber(),
                'facebook_url' => 'https://facebook.com/'.fake()->slug(),
                'twitter_url' => 'https://twitter.com/'.fake()->slug(),
                'instagram_url' => 'https://instagram.com/'.fake()->slug(),
                'footer_text' => fake()->sentence(),
            ];

            foreach ($settings as $key => $value) {
                $site->settings()->create(['key' => $key, 'value' => $value]);
            }
        });
    }
}
