<?php

namespace Database\Factories;

use App\Models\Site;
use App\Models\Taxonomy;
use Illuminate\Database\Eloquent\Factories\Factory;

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
