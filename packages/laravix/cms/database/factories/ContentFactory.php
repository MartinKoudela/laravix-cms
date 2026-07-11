<?php

namespace Laravix\Cms\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Laravix\Cms\Enums\ContentStatus;
use Laravix\Cms\Models\Content;
use Laravix\Cms\Models\Site;
use Laravix\Cms\Models\User;

/**
 * @extends Factory<Content>
 */
class ContentFactory extends Factory
{
    public function definition(): array
    {
        $status = fake()->randomElement(ContentStatus::cases());

        return [
            'site_id' => Site::query()->inRandomOrder()->value('id'),
            'created_by' => User::query()->inRandomOrder()->value('id'),
            'type' => fake()->randomElement(['page', 'post', 'history']),
            'title' => fake()->word(),
            'slug' => fake()->unique()->slug(),
            'status' => $status,
            'published_at' => fake()->dateTimeBetween('-1 year'),
        ];
    }
}
