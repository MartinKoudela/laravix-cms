<?php

namespace Database\Factories;

use App\Models\Media;
use App\Models\Site;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

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
            'path' => 'files/' . fake()->uuid() . '.' . fake()->fileExtension(),
            'disk' => 'public',
            'size' => fake()->numberBetween(10000, 5000000),
            'created_by' => User::query()->inRandomOrder()->value('id'),
        ];
    }
}
