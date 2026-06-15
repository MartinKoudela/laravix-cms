<?php

namespace Database\Factories;

use App\Enums\FieldType;
use App\Models\ContentTypeField;
use App\Models\Site;
use Illuminate\Database\Eloquent\Factories\Factory;

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
