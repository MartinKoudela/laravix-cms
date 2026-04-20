<?php

namespace Database\Factories;

use App\Enums\ContentStatus;
use App\Models\Content;
use App\Models\Site;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

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
            'published_at' =>  fake()->dateTimeBetween('-1 year'),
        ];
    }
}
