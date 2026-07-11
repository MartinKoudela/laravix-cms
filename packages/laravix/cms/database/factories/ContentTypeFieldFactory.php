<?php

namespace Laravix\Cms\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Laravix\Cms\Enums\FieldType;
use Laravix\Cms\Models\ContentTypeField;
use Laravix\Cms\Models\Site;

/**
 * @extends Factory<ContentTypeField>
 */
class ContentTypeFieldFactory extends Factory
{
    public function definition(): array
    {
        return [
            'site_id' => Site::factory(),
            'content_type' => fake()->randomElement(['page', 'post', 'archive']),
            'key' => fake()->unique()->slug(2),
            'label' => fake()->words(2, true),
            'type' => fake()->randomElement(FieldType::cases())->value,
            'group' => null,
            'hint' => null,
            'config' => null,
            'required' => false,
            'sort_order' => fake()->numberBetween(0, 100),
        ];
    }
}
