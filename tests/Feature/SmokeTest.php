<?php

use Laravix\Cms\Enums\ContentStatus;
use Laravix\Cms\Enums\SiteMode;
use Laravix\Cms\Models\Content;
use Laravix\Cms\Models\Site;
use Laravix\Cms\Models\SiteApiToken;
use Laravix\Cms\Models\User;

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

test('admin dashboard renders without raw translation keys', function () {
    $site = Site::factory()->create(['domain' => 'localhost', 'theme' => 'default']);
    $admin = User::factory()->create(['is_super_admin' => true]);

    $html = $this->actingAs($admin)
        ->get('/admin/'.$site->id)
        ->assertSuccessful()
        ->getContent();

    preg_match_all('/laravix::[a-zA-Z0-9_.]+/', $html, $matches);

    expect(array_unique($matches[0]))->toBe([]);
});

test('admin navigation is complete in non-default locale', function () {
    $site = Site::factory()->create(['domain' => 'localhost', 'theme' => 'default']);
    $admin = User::factory()->create(['is_super_admin' => true]);

    $html = $this->actingAs($admin)
        ->get('/admin/'.$site->id.'?locale=cs')
        ->assertSuccessful()
        ->getContent();

    foreach (['Nastavení', 'Uživatelé', 'Navigace', 'Pozvánky', 'Aktivita'] as $label) {
        expect($html)->toContain($label);
    }
});

test('content edit page renders without raw translation keys', function () {
    $site = Site::factory()->create(['domain' => 'localhost', 'theme' => 'default']);
    $admin = User::factory()->create(['is_super_admin' => true]);

    $content = Content::factory()->create([
        'site_id' => $site->id,
        'created_by' => $admin->id,
        'type' => 'page',
        'status' => ContentStatus::PUBLISHED,
        'published_at' => now()->subDay(),
    ]);

    $html = $this->actingAs($admin)
        ->get('/admin/'.$site->id.'/contents/'.$content->id.'/edit')
        ->assertSuccessful()
        ->getContent();

    preg_match_all('/laravix::[a-zA-Z0-9_.]+/', $html, $matches);

    expect(array_unique($matches[0]))->toBe([]);
});
