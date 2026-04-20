<?php

namespace Database\Factories;

use App\Models\Content;
use App\Models\ContentField;
use Illuminate\Database\Eloquent\Factories\Factory;

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
            'seo_title' => fake()->sentence(5),
            'seo_description' => fake()->sentence(),
            'featured_image' => 'images/'.fake()->uuid().'.jpg',
        ];

        $key = fake()->unique()->randomElement(array_keys($fields));

        return [
            'content_id' => Content::query()->inRandomOrder()->value('id'),
            'key' => $key,
            'value' => $fields[$key],
        ];
    }
}
