<?php

namespace Laravix\Cms\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Laravix\Cms\Models\Media;
use Laravix\Cms\Models\Site;
use Laravix\Cms\Models\User;

/**
 * @extends Factory<Media>
 */
class MediaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'site_id' => Site::query()->inRandomOrder()->value('id'),
            'name' => fake()->word(),
            'mime_type' => fake()->randomElement(['image/jpeg', 'image/png', 'image/webp', 'application/pdf']),
            'path' => 'files/'.fake()->uuid().'.'.fake()->fileExtension(),
            'disk' => 'public',
            'size' => fake()->numberBetween(10000, 5000000),
            'created_by' => User::query()->inRandomOrder()->value('id'),
        ];
    }
}
