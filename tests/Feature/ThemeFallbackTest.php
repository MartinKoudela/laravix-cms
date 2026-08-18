<?php

use Illuminate\Support\Facades\View;
use Laravix\Cms\CmsServiceProvider;
use Laravix\Cms\Models\Site;
use Laravix\Cms\Support\ThemeManifest;

beforeEach(function () {
    $this->themePath = base_path('themes/pest-fallback');

    mkdir($this->themePath.'/views/page', 0755, true);
    file_put_contents($this->themePath.'/views/page/show.blade.php', 'OWN PAGE VIEW');
    file_put_contents($this->themePath.'/theme.json', json_encode([
        'name' => 'Pest Fallback',
        'version' => '2.1.0',
        'author' => 'Pest',
    ]));

    $this->app->register(CmsServiceProvider::class, force: true);
});

afterEach(function () {
    removeDirectory($this->themePath);
    ThemeManifest::flush();
});

test('a theme resolves its own view when it has one', function () {
    expect(View::exists('themes.pest-fallback::page.show'))->toBeTrue()
        ->and(trim(view('themes.pest-fallback::page.show')->render()))->toBe('OWN PAGE VIEW');
});

test('a missing view falls back to the default theme instead of blowing up', function () {
    expect(View::exists('themes.pest-fallback::blocks.hero'))->toBeTrue();

    $resolved = View::getFinder()->find('themes.pest-fallback::blocks.hero');

    expect($resolved)->toBe(base_path('themes/default/views/blocks/hero.blade.php'));
});

test('every block view shipped by the default theme is reachable from a bare theme', function () {
    $blocks = glob(base_path('themes/default/views/blocks/*.blade.php'));

    expect($blocks)->not->toBeEmpty();

    foreach ($blocks as $block) {
        $name = basename($block, '.blade.php');

        expect(View::exists("themes.pest-fallback::blocks.{$name}"))->toBeTrue();
    }
});

test('the default theme does not get itself as a duplicate fallback', function () {
    expect(View::getFinder()->getHints()['themes.default'])->toBe([base_path('themes/default/views')]);
});

test('the theme name comes from theme.json, not the folder name', function () {
    expect(Site::availableThemes())->toHaveKey('pest-fallback')
        ->and(Site::availableThemes()['pest-fallback'])->toBe('Pest Fallback')
        ->and(ThemeManifest::find('pest-fallback')->byline())->toBe('2.1.0 · Pest');
});

test('a folder without theme.json is not a theme', function () {
    $orphan = base_path('themes/pest-orphan');
    mkdir($orphan.'/views', 0755, true);
    ThemeManifest::flush();

    try {
        expect(ThemeManifest::find('pest-orphan'))->toBeNull()
            ->and(Site::availableThemes())->not->toHaveKey('pest-orphan');
    } finally {
        removeDirectory($orphan);
        ThemeManifest::flush();
    }
});

test('a theme.json without a name is rejected', function () {
    file_put_contents($this->themePath.'/theme.json', json_encode(['version' => '1.0.0']));
    ThemeManifest::flush();

    expect(ThemeManifest::find('pest-fallback'))->toBeNull();
});
