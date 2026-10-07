<?php

use Laravix\Cms\Enums\SiteRole;
use Laravix\Cms\Models\Content;
use Laravix\Cms\Models\Site;
use Laravix\Cms\Models\User;
use Laravix\Cms\Support\ThemeManifest;

beforeEach(function () {
    $this->themePath = base_path('themes/pest-canvas');

    mkdir($this->themePath.'/dist', 0755, true);
    file_put_contents($this->themePath.'/theme.json', json_encode(['name' => 'Pest Canvas']));
    file_put_contents($this->themePath.'/dist/app.css', ':root{--lx-color-primary:#c2410c}');

    ThemeManifest::flush();
});

afterEach(function () {
    removeDirectory($this->themePath);
    ThemeManifest::flush();
});

function openBuilder(Site $site): array
{
    $user = User::factory()->create();
    $user->sites()->attach($site, ['role' => SiteRole::ADMIN->value]);
    $content = Content::factory()->for($site)->create(['created_by' => $user->id]);

    return test()->actingAs($user)
        ->get(route('builder.edit', [$site, $content]))
        ->assertSuccessful()
        ->viewData('canvasStyles');
}

test('the builder canvas loads the site theme stylesheet after the core one', function () {
    $styles = openBuilder(Site::factory()->create(['theme' => 'pest-canvas']));

    expect($styles)->toHaveCount(2)
        ->and($styles[0])->toContain('app.css')
        ->and($styles[1])->toContain('/themes/pest-canvas/app.css?v=');
});

test('the builder canvas falls back to the core stylesheet when the theme has no built css', function () {
    $styles = openBuilder(Site::factory()->create(['theme' => 'no-such-theme']));

    expect($styles)->toHaveCount(1)
        ->and($styles[0])->not->toContain('/themes/');
});
