<?php

use Filament\Facades\Filament;
use Illuminate\Validation\ValidationException;
use Laravix\Cms\Enums\SiteRole;
use Laravix\Cms\Filament\Pages\Tenancy\RegisterSite;
use Laravix\Cms\Filament\Resources\Roles\Pages\CreateRole;
use Laravix\Cms\Filament\Resources\Roles\Pages\EditRole;
use Laravix\Cms\Filament\Resources\Roles\Pages\ListRoles;
use Laravix\Cms\Filament\Resources\Roles\RoleResource;
use Laravix\Cms\Filament\Resources\Users\Pages\EditUser;
use Laravix\Cms\Models\Content;
use Laravix\Cms\Models\Role;
use Laravix\Cms\Models\Site;
use Laravix\Cms\Models\User;
use Laravix\Cms\Models\UserInvitation;
use Laravix\Cms\Support\PermissionRegistry;
use Livewire\Livewire;

beforeEach(function () {
    $this->site = Site::factory()->create();
});

function memberWithPermissions(Site $site, array $permissions, string $name = 'Custom'): User
{
    $role = Role::factory()->for($site)->create(['name' => $name, 'permissions' => $permissions]);
    $user = User::factory()->create();
    $user->sites()->attach($site, ['role' => $role->slug]);

    return $user;
}

function actAsMember(Site $site, User $user): void
{
    test()->actingAs($user);
    Filament::setCurrentPanel('admin');
    Filament::setTenant($site);
}

test('every site is created with the three system roles', function () {
    $slugs = $this->site->roles()->pluck('slug')->sort()->values()->all();

    expect($slugs)->toBe(['admin', 'editor', 'viewer'])
        ->and($this->site->roles()->where('is_system', false)->count())->toBe(0)
        ->and(Role::findBySlug($this->site, 'admin')->permissions)->toBe([Role::WILDCARD]);
});

test('system role defaults follow the permission registry', function () {
    $editor = Role::findBySlug($this->site, SiteRole::EDITOR->value);
    $viewer = Role::findBySlug($this->site, SiteRole::VIEWER->value);

    expect($editor->allows('content.publish'))->toBeTrue()
        ->and($editor->allows('content.delete'))->toBeFalse()
        ->and($editor->allows('settings.manage'))->toBeFalse()
        ->and($viewer->allows('content.view'))->toBeTrue()
        ->and($viewer->allows('content.create'))->toBeFalse()
        ->and($viewer->permissions)->toBe(PermissionRegistry::defaultsFor(SiteRole::VIEWER));
});

test('a custom role gates policies by its permission list', function () {
    $user = memberWithPermissions($this->site, ['content.view', 'content.delete']);
    actAsMember($this->site, $user);

    $content = Content::factory()->for($this->site)->create(['created_by' => $user->id]);

    expect($user->can('view', $content))->toBeTrue()
        ->and($user->can('create', Content::class))->toBeFalse()
        ->and($user->can('update', $content))->toBeFalse()
        ->and($user->can('delete', $content))->toBeTrue()
        ->and($user->can('publish', $content))->toBeFalse()
        ->and($user->hasSitePermission($this->site, 'settings.manage'))->toBeFalse();
});

test('a role without the view permission hides the resource entirely', function () {
    $user = memberWithPermissions($this->site, ['content.view']);
    actAsMember($this->site, $user);

    $this->get("/admin/{$this->site->id}/media")->assertForbidden();
    $this->get("/admin/{$this->site->id}/contents")->assertOk();
});

test('unknown permission keys are dropped when a role is saved', function () {
    $role = Role::factory()->for($this->site)->create(['permissions' => ['content.view', 'nope.everything', 'content.view']]);

    expect($role->fresh()->permissions)->toBe(['content.view']);
});

test('a custom role gets a slug unique within the site', function () {
    $first = Role::factory()->for($this->site)->create(['name' => 'Copy Writer']);
    $second = Role::factory()->for($this->site)->create(['name' => 'Copy writer']);
    $elsewhere = Role::factory()->create(['name' => 'Copy Writer']);

    expect($first->slug)->toBe('copy-writer')
        ->and($second->slug)->toBe('copy-writer-2')
        ->and($elsewhere->slug)->toBe('copy-writer');
});

test('an admin can create a role through the panel', function () {
    $admin = memberWithPermissions($this->site, [Role::WILDCARD]);
    actAsMember($this->site, $admin);

    Livewire::test(CreateRole::class)
        ->fillForm([
            'name' => 'Publisher',
            'grants' => [
                'content' => ['content.view', 'content.update', 'content.publish'],
                'media' => ['media.view'],
            ],
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    $role = Role::findBySlug($this->site, 'publisher');

    expect($role)->not->toBeNull()
        ->and($role->is_system)->toBeFalse()
        ->and($role->permissions)->toEqualCanonicalizing(['content.view', 'content.update', 'content.publish', 'media.view']);
});

test('the preset select copies a system role into the grid', function () {
    $admin = memberWithPermissions($this->site, [Role::WILDCARD]);
    actAsMember($this->site, $admin);

    Livewire::test(CreateRole::class)
        ->fillForm(['name' => 'Junior editor'])
        ->set('data.preset', SiteRole::VIEWER->value)
        ->call('create')
        ->assertHasNoFormErrors();

    expect(Role::findBySlug($this->site, 'junior-editor')->permissions)
        ->toEqualCanonicalizing(PermissionRegistry::defaultsFor(SiteRole::VIEWER));
});

test('a role manager cannot grant a permission they do not hold', function () {
    $manager = memberWithPermissions($this->site, ['roles.manage', 'content.view'], 'Manager');
    actAsMember($this->site, $manager);

    Livewire::test(CreateRole::class)
        ->fillForm([
            'name' => 'Sneaky',
            'grants' => ['content' => ['content.view', 'content.delete']],
        ])
        ->call('create')
        ->assertHasFormErrors();

    expect(Role::findBySlug($this->site, 'sneaky'))->toBeNull();
});

test('the server refuses to persist a permission the acting user does not hold', function () {
    $manager = memberWithPermissions($this->site, ['roles.manage', 'content.view'], 'Manager');
    actAsMember($this->site, $manager);

    $page = new CreateRole;

    $flatten = (fn (array $data): array => $this->flattenPermissions($data))->bindTo($page, $page);

    expect(fn () => $flatten(['name' => 'Sneaky', 'grants' => ['content' => ['content.delete']]]))
        ->toThrow(ValidationException::class);
});

test('editing a role keeps permissions the editor could not see', function () {
    $role = Role::factory()->for($this->site)->create(['name' => 'Mixed', 'permissions' => ['content.view', 'settings.manage']]);
    $manager = memberWithPermissions($this->site, ['roles.manage', 'content.view', 'content.update'], 'Manager');
    actAsMember($this->site, $manager);

    Livewire::test(EditRole::class, ['record' => $role->getRouteKey()])
        ->fillForm(['grants' => ['content' => ['content.view', 'content.update']]])
        ->call('save')
        ->assertHasNoFormErrors();

    expect($role->fresh()->permissions)->toEqualCanonicalizing(['content.view', 'content.update', 'settings.manage']);
});

test('the admin role cannot be edited and system roles cannot be deleted', function () {
    $admin = memberWithPermissions($this->site, [Role::WILDCARD]);
    actAsMember($this->site, $admin);

    $adminRole = Role::findBySlug($this->site, SiteRole::ADMIN->value);
    $editorRole = Role::findBySlug($this->site, SiteRole::EDITOR->value);

    expect(RoleResource::canEdit($adminRole))->toBeFalse()
        ->and(RoleResource::canEdit($editorRole))->toBeTrue()
        ->and(RoleResource::canDelete($editorRole))->toBeFalse();
});

test('a role that is still assigned cannot be deleted from the table', function () {
    $admin = memberWithPermissions($this->site, [Role::WILDCARD]);
    actAsMember($this->site, $admin);

    $role = Role::factory()->for($this->site)->create(['name' => 'Busy']);
    $member = User::factory()->create();
    $member->sites()->attach($this->site, ['role' => $role->slug]);

    Livewire::test(ListRoles::class)
        ->callTableAction('delete', $role);

    expect($role->fresh())->not->toBeNull();

    $member->sites()->detach($this->site);
    UserInvitation::create([
        'email' => 'pending@example.com',
        'role' => $role->slug,
        'token' => UserInvitation::generateToken(),
        'site_id' => $this->site->id,
        'invited_by' => $admin->id,
        'expires_at' => now()->addDay(),
    ]);

    expect($role->fresh()->isInUse())->toBeTrue();
});

test('an unused custom role can be deleted', function () {
    $admin = memberWithPermissions($this->site, [Role::WILDCARD]);
    actAsMember($this->site, $admin);

    $role = Role::factory()->for($this->site)->create(['name' => 'Idle']);

    Livewire::test(ListRoles::class)
        ->callTableAction('delete', $role);

    expect(Role::find($role->id))->toBeNull();
});

test('assignable roles exclude anything above the acting user', function () {
    $manager = memberWithPermissions($this->site, ['users.manage', 'content.view'], 'Manager');
    Role::factory()->for($this->site)->create(['name' => 'Reader', 'permissions' => ['content.view']]);

    $options = Role::assignableOptions($manager, $this->site);

    expect($options)->toHaveKey('reader')
        ->and($options)->not->toHaveKey('admin')
        ->and($options)->not->toHaveKey('editor');

    $superAdmin = User::factory()->create(['is_super_admin' => true]);

    expect(Role::assignableOptions($superAdmin, $this->site))->toHaveKeys(['admin', 'editor', 'viewer', 'reader']);
});

test('the last admin of a site cannot be demoted', function () {
    $admin = User::factory()->create();
    $admin->sites()->attach($this->site, ['role' => SiteRole::ADMIN->value]);
    actAsMember($this->site, $admin);

    Livewire::test(EditUser::class, ['record' => $admin->getRouteKey()])
        ->fillForm(['role' => SiteRole::EDITOR->value])
        ->call('save');

    expect($admin->fresh()->roleSlugForSite($this->site))->toBe(SiteRole::ADMIN->value);

    $second = User::factory()->create();
    $second->sites()->attach($this->site, ['role' => SiteRole::ADMIN->value]);

    Livewire::test(EditUser::class, ['record' => $admin->getRouteKey()])
        ->fillForm(['role' => SiteRole::EDITOR->value])
        ->call('save')
        ->assertHasNoFormErrors();

    expect($admin->fresh()->roleSlugForSite($this->site))->toBe(SiteRole::EDITOR->value);
});

test('a user manager cannot hand out a role above their own', function () {
    $manager = memberWithPermissions($this->site, ['users.manage', 'content.view'], 'Manager');
    actAsMember($this->site, $manager);

    $member = User::factory()->create();
    $member->sites()->attach($this->site, ['role' => SiteRole::VIEWER->value]);

    Livewire::test(EditUser::class, ['record' => $member->getRouteKey()])
        ->fillForm(['role' => SiteRole::ADMIN->value])
        ->call('save');

    expect($member->fresh()->roleSlugForSite($this->site))->toBe(SiteRole::VIEWER->value);
});

test('registering a site through the panel makes the creator its admin', function () {
    $superAdmin = User::factory()->create(['is_super_admin' => true]);
    actAsMember($this->site, $superAdmin);

    Livewire::test(RegisterSite::class)
        ->fillForm(['name' => 'Fresh', 'domain' => 'fresh.test', 'mode' => 'theme', 'theme' => 'default'])
        ->call('register')
        ->assertHasNoFormErrors();

    $site = Site::where('domain', 'fresh.test')->first();

    expect($site)->not->toBeNull()
        ->and($superAdmin->roleSlugForSite($site))->toBe(SiteRole::ADMIN->value)
        ->and($site->roles()->count())->toBe(3);
});
