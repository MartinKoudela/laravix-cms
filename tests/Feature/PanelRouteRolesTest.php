<?php

use Laravix\Cms\Enums\SiteRole;
use Laravix\Cms\Models\Site;
use Laravix\Cms\Models\User;

beforeEach(function () {
    $this->site = Site::factory()->create();
});

function signInWithRole(Site $site, SiteRole $role): User
{
    $user = User::factory()->create();
    $user->sites()->attach($site, ['role' => $role->value]);

    test()->actingAs($user);

    return $user;
}


dataset('panel paths', [
    'contents' => ['contents', ['admin' => 200, 'editor' => 200, 'viewer' => 200]],
    'contents/create' => ['contents/create', ['admin' => 200, 'editor' => 200, 'viewer' => 403]],
    'taxonomies' => ['taxonomies', ['admin' => 200, 'editor' => 200, 'viewer' => 200]],
    'taxonomies/create' => ['taxonomies/create', ['admin' => 200, 'editor' => 200, 'viewer' => 403]],
    'media' => ['media', ['admin' => 200, 'editor' => 200, 'viewer' => 200]],
    'media/create' => ['media/create', ['admin' => 200, 'editor' => 200, 'viewer' => 403]],
    'content-type-fields' => ['content-type-fields', ['admin' => 200, 'editor' => 200, 'viewer' => 200]],
    'content-type-fields/create' => ['content-type-fields/create', ['admin' => 200, 'editor' => 200, 'viewer' => 403]],
    'custom-code-blocks' => ['custom-code-blocks', ['admin' => 200, 'editor' => 200, 'viewer' => 200]],
    'custom-code-blocks/create' => ['custom-code-blocks/create', ['admin' => 200, 'editor' => 200, 'viewer' => 403]],

    'settings' => ['settings', ['admin' => 200, 'editor' => 403, 'viewer' => 403]],
    'navigation' => ['navigation/navigations', ['admin' => 200, 'editor' => 403, 'viewer' => 403]],
    'activity-logs' => ['activity-logs', ['admin' => 200, 'editor' => 403, 'viewer' => 403]],
    'user-invitations' => ['user-invitations', ['admin' => 200, 'editor' => 403, 'viewer' => 403]],
    'users' => ['users', ['admin' => 200, 'editor' => 403, 'viewer' => 403]],
    'users/create' => ['users/create', ['admin' => 200, 'editor' => 403, 'viewer' => 403]],

    'sites' => ['sites', ['admin' => 403, 'editor' => 403, 'viewer' => 403]],
    'sites/create' => ['sites/create', ['admin' => 403, 'editor' => 403, 'viewer' => 403]],
]);

test('a panel path answers every role as expected', function (string $path, array $expected) {
    foreach ($expected as $role => $status) {
        signInWithRole($this->site, SiteRole::from($role));

        $this->get("/admin/{$this->site->id}/{$path}")
            ->assertStatus($status, "role [{$role}] on [{$path}]");
    }
})->with('panel paths');

test('a super admin reaches the sites resource', function () {
    $this->actingAs(User::factory()->create(['is_super_admin' => true]));

    $this->get("/admin/{$this->site->id}/sites")->assertOk();
    $this->get("/admin/{$this->site->id}/sites/create")->assertOk();
});

test('a member of another site cannot reach this tenant', function () {
    signInWithRole(Site::factory()->create(), SiteRole::ADMIN);


    $this->get("/admin/{$this->site->id}/contents")->assertNotFound();
});

test('a user with no site at all cannot reach the panel', function () {
    $this->actingAs(User::factory()->create());

    $this->get("/admin/{$this->site->id}/contents")->assertForbidden();
});
