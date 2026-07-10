<?php

use App\Enums\ContentStatus;
use App\Models\Content;
use App\Models\Site;
use App\Models\User;

test('the application returns a successful response', function () {
    $site = Site::factory()->create(['domain' => 'localhost', 'theme' => 'default']);
    $user = User::factory()->create();

    Content::factory()->create([
        'site_id' => $site->id,
        'created_by' => $user->id,
        'type' => 'page',
        'slug' => '/',
        'is_homepage' => true,
        'status' => ContentStatus::PUBLISHED,
        'published_at' => now()->subDay(),
    ]);

    $response = $this->get('/');

    $response->assertSuccessful();
});
