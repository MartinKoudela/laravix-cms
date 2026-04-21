<?php

namespace Database\Seeders;

use App\Models\Content;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ContentSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        // TODO: přepsat na na použití ->for() nebo alternativu pro vazbu, bude jednodušší zápis
        Content::factory()->count(20)->create()->each(function (Content $content) {
            $fields = [
                'body' => fake()->paragraphs(3, true),
                'excerpt' => fake()->sentence(),
                'seo_title' => fake()->word(),
                'seo_description' => fake()->sentence(),
                'featured_image' => 'images/'.fake()->uuid().'.jpg',
            ];

            foreach ($fields as $key => $value) {
                $content->fields()->create(['key' => $key, 'value' => $value]);
            }
        });
    }
}
