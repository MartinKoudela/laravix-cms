<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Laravix\Cms\Enums\ContentStatus;
use Laravix\Cms\Enums\SiteMode;
use Laravix\Cms\Models\Content;
use Laravix\Cms\Models\Site;
use Laravix\Cms\Models\SiteApiToken;
use Laravix\Cms\Models\User;
use Laravix\Cms\Support\RouteRegistry;

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

test('a plugin route registered through the route registry wins over the cms catch-all', function () {
    Site::factory()->create(['domain' => 'localhost', 'theme' => 'default']);

    RouteRegistry::register(function (): void {
        Route::get('/plugin-smoke', fn () => 'ok')->name('plugin.smoke');
    });

    Route::middleware('web')->group(fn () => RouteRegistry::apply());
    app('router')->getRoutes()->refreshNameLookups();

    $matched = app('router')->getRoutes()->match(
        Request::create('http://localhost/plugin-smoke', 'GET')
    );

    expect($matched->getName())->toBe('plugin.smoke');
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

test('tenant dashboard is not shadowed by the cms catch-all', function () {
    $site = Site::factory()->create(['domain' => 'localhost', 'theme' => 'default']);

    $matched = app('router')->getRoutes()->match(
        Request::create('http://localhost/admin/'.$site->id, 'GET')
    );

    expect($matched->getName())->toBe('filament.admin.pages.dashboard');
});

test('cms catch-all is registered as a fallback route', function () {
    $route = collect(app('router')->getRoutes()->getRoutes())
        ->first(fn ($route) => $route->getName() === 'cms.show');

    expect($route)->not->toBeNull()
        ->and($route->isFallback)->toBeTrue();
});
