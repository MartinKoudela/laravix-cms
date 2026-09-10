<?php

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Laravix\Cms\Enums\SiteRole;
use Laravix\Cms\Models\Content;
use Laravix\Cms\Models\Media;
use Laravix\Cms\Models\Site;
use Laravix\Cms\Models\User;

function builderMember(SiteRole $role, Site $site): User
{
    $user = User::factory()->create();
    $user->sites()->attach($site, ['role' => $role->value]);

    return $user;
}

dataset('writing roles', [
    'admin' => [SiteRole::ADMIN],
    'editor' => [SiteRole::EDITOR],
]);

test('a viewer cannot open the builder', function () {
    $site = Site::factory()->create();
    $user = builderMember(SiteRole::VIEWER, $site);
    $content = Content::factory()->for($site)->create(['created_by' => $user->id]);

    $this->actingAs($user)
        ->get(route('builder.edit', [$site, $content]))
        ->assertForbidden();
});

test('a viewer cannot save through the builder', function () {
    $site = Site::factory()->create();
    $user = builderMember(SiteRole::VIEWER, $site);
    $content = Content::factory()->for($site)->create(['created_by' => $user->id, 'grapesjs_data' => 'original']);

    $this->actingAs($user)
        ->post(route('builder.save', [$site, $content]), [
            'grapesjs_data' => 'tampered',
        ])
        ->assertForbidden();

    expect($content->refresh()->grapesjs_data)->toBe('original');
});

test('a viewer cannot upload through the builder', function () {
    Storage::fake('public');
    $site = Site::factory()->create();

    $this->actingAs(builderMember(SiteRole::VIEWER, $site))
        ->post(route('builder.upload', $site), [
            'file' => UploadedFile::fake()->image('payload.png'),
        ])
        ->assertForbidden();

    expect(Media::count())->toBe(0);
});

test('a non member cannot open the builder', function () {
    $site = Site::factory()->create();
    $stranger = User::factory()->create();
    $content = Content::factory()->for($site)->create(['created_by' => $stranger->id]);

    $this->actingAs($stranger)
        ->get(route('builder.edit', [$site, $content]))
        ->assertForbidden();
});

test('a writing role can save through the builder', function (SiteRole $role) {
    $site = Site::factory()->create();
    $user = builderMember($role, $site);
    $content = Content::factory()->for($site)->create(['created_by' => $user->id, 'grapesjs_data' => 'original']);

    $this->actingAs($user)
        ->post(route('builder.save', [$site, $content]), [
            'grapesjs_data' => 'updated',
        ])
        ->assertOk();

    expect($content->refresh()->grapesjs_data)->toBe('updated');
})->with('writing roles');

test('a writing role can upload through the builder', function (SiteRole $role) {
    Storage::fake('public');
    $site = Site::factory()->create();

    $this->actingAs(builderMember($role, $site))
        ->post(route('builder.upload', $site), [
            'file' => UploadedFile::fake()->image('hero.png'),
        ])
        ->assertOk();

    expect(Media::where('site_id', $site->id)->count())->toBe(1);
})->with('writing roles');
