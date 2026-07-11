<?php

namespace Laravix\Cms\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Laravix\Cms\Models\Content;
use Laravix\Cms\Models\ContentField;

/**
 * @extends Factory<ContentField>
 */
class ContentFieldFactory extends Factory
{
    public function definition(): array
    {
        $fields = [
            'body' => fake()->paragraphs(3, true),
            'excerpt' => fake()->sentence(),
            'meta_title' => fake()->word(),
            'meta_description' => fake()->sentence(),
        ];

        $key = fake()->randomElement(array_keys($fields));

        return [
            'content_id' => Content::query()->inRandomOrder()->value('id'),
            'key' => $key,
            'value' => $fields[$key],
        ];
    }
}
