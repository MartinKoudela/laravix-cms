<?php

use Laravix\Cms\Laravix;
use Laravix\Cms\Support\ThemeManifest;

beforeEach(function () {
    $this->themePath = base_path('themes/pest-assets');
    $this->publicLink = public_path('themes/pest-assets');

    mkdir($this->themePath.'/dist', 0755, true);
    file_put_contents($this->themePath.'/theme.json', json_encode(['name' => 'Pest Assets']));
    file_put_contents($this->themePath.'/dist/app.css', 'body{color:red}');

    ThemeManifest::flush();
});

afterEach(function () {
    if (is_link($this->publicLink)) {
        unlink($this->publicLink);
    }

    removeDirectory($this->themePath);
    ThemeManifest::flush();
});

test('a theme asset resolves to a versioned public url', function () {
    $url = Laravix::themeAsset('app.css', 'pest-assets');

    expect($url)->toContain('/themes/pest-assets/app.css?v=')
        ->and($url)->toContain((string) filemtime($this->themePath.'/dist/app.css'));
});

test('a missing theme asset resolves to null so the layout can skip it', function () {
    expect(Laravix::themeAsset('missing.css', 'pest-assets'))->toBeNull()
        ->and(Laravix::themeAsset('app.css', 'no-such-theme'))->toBeNull();
});

test('the link command exposes dist under public/themes', function () {
    $this->artisan('laravix:theme:link')->assertSuccessful();

    expect(is_link($this->publicLink))->toBeTrue()
        ->and(readlink($this->publicLink))->toBe($this->themePath.'/dist')
        ->and(file_get_contents($this->publicLink.'/app.css'))->toBe('body{color:red}');
});

test('the link command only exposes dist, never the blade sources', function () {
    mkdir($this->themePath.'/views', 0755, true);
    file_put_contents($this->themePath.'/views/secret.blade.php', 'SOURCE');

    $this->artisan('laravix:theme:link')->assertSuccessful();

    expect(file_exists($this->publicLink.'/secret.blade.php'))->toBeFalse()
        ->and(file_exists($this->publicLink.'/../pest-assets/views'))->toBeFalse();
});

test('the link command skips a theme without dist', function () {
    removeDirectory($this->themePath.'/dist');

    $this->artisan('laravix:theme:link')->assertSuccessful();

    expect(file_exists($this->publicLink))->toBeFalse();
});

test('the link command refuses to touch a real directory in its way', function () {
    mkdir($this->publicLink, 0755, true);

    try {
        $this->artisan('laravix:theme:link')
            ->expectsOutputToContain('is not a link')
            ->assertSuccessful();

        expect(is_link($this->publicLink))->toBeFalse();
    } finally {
        removeDirectory($this->publicLink);
    }
});

test('running the link command twice is harmless', function () {
    $this->artisan('laravix:theme:link')->assertSuccessful();
    $this->artisan('laravix:theme:link')->assertSuccessful();

    expect(is_link($this->publicLink))->toBeTrue();
});
