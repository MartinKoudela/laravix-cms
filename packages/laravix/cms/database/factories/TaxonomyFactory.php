<?php

namespace Laravix\Cms\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Laravix\Cms\Models\Site;
use Laravix\Cms\Models\Taxonomy;

/**
 * @extends Factory<Taxonomy>
 */
class TaxonomyFactory extends Factory
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
            'type' => fake()->randomElement(['category', 'tag']),
            'name' => fake()->word(),
            'slug' => fake()->slug(),
        ];

    }
}
