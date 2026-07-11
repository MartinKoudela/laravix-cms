<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Laravix\Cms\Models\Content;
use Laravix\Cms\Models\ContentField;
use Laravix\Cms\Models\Site;
use Laravix\Cms\Models\User;

class ContentSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $users = User::all();

        Site::all()->each(function (Site $site) use ($users) {
            Content::factory()
                ->count(5)
                ->for($site)
                ->for($users->random(), 'author')
                ->has(
                    ContentField::factory()->count(4)->sequence(
                        ['key' => 'body', 'value' => fake()->paragraphs(3, true)],
                        ['key' => 'excerpt', 'value' => fake()->sentence()],
                        ['key' => 'meta_title', 'value' => fake()->word()],
                        ['key' => 'meta_description', 'value' => fake()->sentence()],
                    ),
                    'fields'
                )
                ->create();
        });
    }
}
