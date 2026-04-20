<?php

namespace Database\Factories;

use App\Models\Setting;
use App\Models\Site;
use Illuminate\Database\Eloquent\Factories\Factory;

class SettingFactory extends Factory
{
    protected $model = Setting::class;

    public function definition(): array
    {
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

        $key = fake()->randomElement(array_keys($settings));

        return [
            'site_id' => Site::query()->inRandomOrder()->value('id'),
            'key' => $key,
            'value' => $settings[$key],
        ];
    }
}
