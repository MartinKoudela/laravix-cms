<?php

use Laravix\Cms\Models\Content;
use Laravix\Cms\Models\Site;
use Laravix\Cms\Models\User;

test('installer creates site, super admin and demo content', function () {
    $this->artisan('laravix:install', [
        '--site-name' => 'Fresh Site',
        '--domain' => 'fresh.test',
        '--admin-name' => 'Admin',
        '--admin-email' => 'admin@fresh.test',
        '--admin-password' => 'secret-password',
        '--demo' => true,
        '--no-interaction' => true,
    ])->assertSuccessful();

    $site = Site::where('domain', 'fresh.test')->first();
    $admin = User::where('email', 'admin@fresh.test')->first();

    expect($site)->not->toBeNull()
        ->and($site->name)->toBe('Fresh Site')
        ->and($admin)->not->toBeNull()
        ->and($admin->is_super_admin)->toBeTrue()
        ->and(Content::where('site_id', $site->id)->where('is_homepage', true)->exists())->toBeTrue()
        ->and(Content::where('site_id', $site->id)->where('type', 'post')->count())->toBe(3);
});

test('installer refuses to run on an installed application', function () {
    Site::factory()->create();

    $this->artisan('laravix:install', [
        '--site-name' => 'Another',
        '--domain' => 'another.test',
        '--no-interaction' => true,
    ])->assertFailed();
});
