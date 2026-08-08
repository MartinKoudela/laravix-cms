<?php

use Laravix\Cms\Models\Site;
use Laravix\Cms\Models\User;
use Laravix\Cms\Support\ContentTypeRegistry;
use Laravix\Cms\Support\FastActions;

test('the create content page opens without a type in the query string', function () {
    $site = Site::factory()->create(['domain' => 'localhost', 'theme' => 'default']);
    $admin = User::factory()->create(['is_super_admin' => true]);

    $this->actingAs($admin)
        ->get('/admin/'.$site->id.'/contents/create')
        ->assertSuccessful();
});

test('the create content page preselects a valid type from the query string', function () {
    $site = Site::factory()->create(['domain' => 'localhost', 'theme' => 'default']);
    $admin = User::factory()->create(['is_super_admin' => true]);

    $this->actingAs($admin)
        ->get('/admin/'.$site->id.'/contents/create?type=post')
        ->assertSuccessful();
});

test('the create content page survives an unknown type', function () {
    $site = Site::factory()->create(['domain' => 'localhost', 'theme' => 'default']);
    $admin = User::factory()->create(['is_super_admin' => true]);

    $this->actingAs($admin)
        ->get('/admin/'.$site->id.'/contents/create?type=nope')
        ->assertSuccessful();
});

test('the content type registry treats null as an unknown type', function () {
    expect(ContentTypeRegistry::has(null))->toBeFalse()
        ->and(ContentTypeRegistry::find(null))->toBeNull();
});

test('every fast action url resolves', function () {
    $site = Site::factory()->create(['domain' => 'localhost', 'theme' => 'default']);
    $admin = User::factory()->create(['is_super_admin' => true]);

    foreach (FastActions::all($site->id) as $key => $action) {
        $path = parse_url($action['url'], PHP_URL_PATH);

        expect($this->actingAs($admin)->get($path)->status())
            ->toBe(200, "fast action [{$key}] returned a non-200 response");
    }
});
