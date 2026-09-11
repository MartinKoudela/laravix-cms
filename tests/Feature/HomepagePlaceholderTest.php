<?php

use Laravix\Cms\CmsServiceProvider;
use Laravix\Cms\Enums\ContentStatus;
use Laravix\Cms\Enums\SiteMode;
use Laravix\Cms\Models\Content;
use Laravix\Cms\Models\Site;
use Laravix\Cms\Models\User;
use Laravix\Cms\Support\ThemeManifest;

beforeEach(function () {
    $this->site = Site::factory()->create([
        'name' => 'Acme Corp',
        'domain' => 'localhost',
        'theme' => 'default',
        'mode' => SiteMode::THEME,
    ]);

    $this->author = User::factory()->create();
});

function homepage(Site $site, User $author, array $attributes = []): Content
{
    return Content::factory()->for($site)->create(array_merge([
        'created_by' => $author->id,
        'type' => 'page',
        'title' => 'Welcome',
        'slug' => 'welcome',
        'is_homepage' => true,
        'status' => ContentStatus::PUBLISHED,
        'published_at' => now()->subDay(),
        'grapesjs_html' => '<h1>REAL HOMEPAGE</h1>',
    ], $attributes));
}

test('a published homepage with content is served, never the placeholder', function () {
    homepage($this->site, $this->author);

    $this->get('/')
        ->assertOk()
        ->assertSee('REAL HOMEPAGE')
        ->assertDontSee(__('laravix::placeholder.body'));
});

test('a site without a homepage shows the placeholder', function () {
    $this->get('/')
        ->assertOk()
        ->assertSee('Acme Corp')
        ->assertSee(__('laravix::placeholder.body'))
        ->assertSee('noindex', false);
});

test('a draft homepage shows the placeholder', function () {
    homepage($this->site, $this->author, ['status' => ContentStatus::DRAFT]);

    $this->get('/')
        ->assertOk()
        ->assertSee(__('laravix::placeholder.body'))
        ->assertDontSee('REAL HOMEPAGE');
});

test('a published homepage with no body shows the placeholder', function () {
    homepage($this->site, $this->author, ['grapesjs_html' => null, 'blocks' => []]);

    $this->get('/')
        ->assertOk()
        ->assertSee(__('laravix::placeholder.body'));
});

test('an unknown slug on a site with content is a plain 404, not the placeholder', function () {
    homepage($this->site, $this->author);

    $this->get('/does-not-exist')
        ->assertNotFound()
        ->assertDontSee(__('laravix::placeholder.body'));
});

test('a headless site answers its root with the placeholder as a 404', function () {
    $this->site->update(['mode' => SiteMode::HEADLESS]);

    $this->get('/')
        ->assertNotFound()
        ->assertSee(__('laravix::placeholder.body'))
        ->assertDontSee('/api/v1');
});

test('the developer hint is shown only on a local install', function () {
    app()->detectEnvironment(fn () => 'local');

    $this->get('/')
        ->assertSee(__('laravix::placeholder.hints.no_homepage'))
        ->assertSee("/admin/{$this->site->id}/contents");

    app()->detectEnvironment(fn () => 'production');

    $this->get('/')
        ->assertDontSee(__('laravix::placeholder.hints.no_homepage'))
        ->assertDontSee('/admin/');
});

test('the hint names the actual reason', function () {
    app()->detectEnvironment(fn () => 'local');

    homepage($this->site, $this->author, ['status' => ContentStatus::DRAFT]);

    $this->get('/')->assertSee(__('laravix::placeholder.hints.homepage_draft'));
});

test('a theme can override the placeholder view', function () {
    $themePath = base_path('themes/pest-placeholder');
    mkdir($themePath.'/views', 0755, true);
    file_put_contents($themePath.'/views/placeholder.blade.php', 'THEME PLACEHOLDER');
    file_put_contents($themePath.'/theme.json', json_encode(['name' => 'Pest', 'version' => '1.0.0']));
    $this->app->register(CmsServiceProvider::class, force: true);

    $this->site->update(['theme' => 'pest-placeholder']);

    try {
        $this->get('/')->assertOk()->assertSee('THEME PLACEHOLDER');
    } finally {
        removeDirectory($themePath);
        ThemeManifest::flush();
    }
});
