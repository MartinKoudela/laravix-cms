<?php

use App\Enums\ContentStatus;
use App\Enums\SiteMode;
use App\Models\Content;
use App\Models\Site;
use App\Models\SiteApiToken;
use App\Models\User;

test('application route wins over cms catch-all', function () {
    Site::factory()->create(['domain' => 'localhost', 'theme' => 'default']);

    $this->get('/robots.txt')->assertSuccessful();
});

test('api pages endpoint responds with valid token', function () {
    $site = Site::factory()->create([
        'domain' => 'headless.test',
        'mode' => SiteMode::HEADLESS,
    ]);
    $user = User::factory()->create();

    Content::factory()->create([
        'site_id' => $site->id,
        'created_by' => $user->id,
        'type' => 'page',
        'title' => 'Smoke Page',
        'slug' => 'smoke-page',
        'status' => ContentStatus::PUBLISHED,
        'published_at' => now()->subDay(),
    ]);

    $result = SiteApiToken::generateFor($site, 'Smoke');

    $this->getJson('/api/v1/pages', [
        'X-Site-Domain' => 'headless.test',
        'Authorization' => 'Bearer '.$result['plaintext'],
    ])
        ->assertSuccessful()
        ->assertJsonFragment(['title' => 'Smoke Page']);
});

test('docs plugin route registered through route registry responds', function () {
    Site::factory()->create(['domain' => 'localhost', 'theme' => 'default']);

    $this->get('/docs')->assertSuccessful();
});
