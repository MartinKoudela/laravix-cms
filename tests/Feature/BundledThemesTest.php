<?php

use Illuminate\Support\Facades\View;
use Laravix\Cms\CmsServiceProvider;
use Laravix\Cms\Laravix;
use Laravix\Cms\Support\ThemeManifest;

beforeEach(function () {
    $this->bundledPath = ThemeManifest::bundledPath().'/pest-bundled';
    $this->appPath = base_path('themes/pest-bundled');

    mkdir($this->bundledPath.'/views/page', 0755, true);
    mkdir($this->bundledPath.'/dist', 0755, true);
    file_put_contents($this->bundledPath.'/theme.json', json_encode(['name' => 'Pest Bundled']));
    file_put_contents($this->bundledPath.'/views/page/show.blade.php', 'BUNDLED PAGE VIEW');
    file_put_contents($this->bundledPath.'/dist/app.css', ':root{--lx-color-primary:#c2410c}');

    ThemeManifest::flush();
});

afterEach(function () {
    removeDirectory($this->bundledPath);
    removeDirectory($this->appPath);
    ThemeManifest::flush();
});

test('a theme shipped inside the package is available without publishing it', function () {
    $theme = ThemeManifest::find('pest-bundled');

    expect($theme)->not->toBeNull()
        ->and($theme->bundled)->toBeTrue()
        ->and($theme->path('views'))->toBe($this->bundledPath.'/views')
        ->and(Laravix::themeAsset('app.css', 'pest-bundled'))->toContain('/themes/pest-bundled/app.css?v=');
});

test('a bundled theme registers its views and still falls back to the default theme', function () {
    $this->app->register(CmsServiceProvider::class, force: true);

    expect(trim(view('themes.pest-bundled::page.show')->render()))->toBe('BUNDLED PAGE VIEW')
        ->and(View::exists('themes.pest-bundled::blocks.hero'))->toBeTrue();
});

test('a theme folder in the application overrides the bundled theme with the same key', function () {
    mkdir($this->appPath, 0755, true);
    file_put_contents($this->appPath.'/theme.json', json_encode(['name' => 'My Own Copy']));
    ThemeManifest::flush();

    $theme = ThemeManifest::find('pest-bundled');

    expect($theme->name)->toBe('My Own Copy')
        ->and($theme->bundled)->toBeFalse()
        ->and(Laravix::themeAsset('app.css', 'pest-bundled'))->toBeNull();
});
