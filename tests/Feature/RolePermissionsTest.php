<?php

use Laravix\Cms\Enums\SiteRole;
use Laravix\Cms\Filament\Resources\ContentTypeFields\ContentTypeFieldResource;
use Laravix\Cms\Filament\Resources\CustomCodeBlocks\CustomCodeBlockResource;
use Laravix\Cms\Models\Site;
use Laravix\Cms\Models\User;

function actAsRole(SiteRole $role): Site
{
    $site = Site::factory()->create();
    $user = User::factory()->create();
    $user->sites()->attach($site, ['role' => $role->value]);

    auth()->login($user);
    filament()->setTenant($site);

    return $site;
}

dataset('gated resources', [
    'custom fields' => [ContentTypeFieldResource::class],
    'custom code blocks' => [CustomCodeBlockResource::class],
]);

test('a viewer cannot create through the filament resource', function (string $resource) {
    actAsRole(SiteRole::VIEWER);

    expect($resource::canCreate())->toBeFalse();
})->with('gated resources');

test('a viewer cannot bulk delete through the filament resource', function (string $resource) {
    actAsRole(SiteRole::VIEWER);

    expect($resource::canDeleteAny())->toBeFalse();
})->with('gated resources');

test('a viewer can still browse the filament resource', function (string $resource) {
    actAsRole(SiteRole::VIEWER);

    expect($resource::canViewAny())->toBeTrue();
})->with('gated resources');

test('an editor can create but not bulk delete', function (string $resource) {
    actAsRole(SiteRole::EDITOR);

    expect($resource::canCreate())->toBeTrue()
        ->and($resource::canDeleteAny())->toBeFalse();
})->with('gated resources');

test('an admin can create and bulk delete', function (string $resource) {
    actAsRole(SiteRole::ADMIN);

    expect($resource::canCreate())->toBeTrue()
        ->and($resource::canDeleteAny())->toBeTrue();
})->with('gated resources');
