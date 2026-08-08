<?php

use Illuminate\Process\PendingProcess;
use Illuminate\Support\Facades\Process;
use Laravix\Cms\Models\Site;
use Laravix\Cms\Models\User;

test('installer creates site and super admin', function () {
    $this->artisan('laravix:install', [
        '--site-name' => 'Fresh Site',
        '--domain' => 'fresh.test',
        '--admin-name' => 'Admin',
        '--admin-email' => 'admin@fresh.test',
        '--admin-password' => 'secret-password',
        '--no-interaction' => true,
    ])->assertSuccessful();

    $site = Site::where('domain', 'fresh.test')->first();
    $admin = User::where('email', 'admin@fresh.test')->first();

    expect($site)->not->toBeNull()
        ->and($site->name)->toBe('Fresh Site')
        ->and($admin)->not->toBeNull()
        ->and($admin->is_super_admin)->toBeTrue();
});

test('installer offers a free laravix.com reference shout-out and opens it in the browser', function () {
    Process::fake();

    $this->artisan('laravix:install', [
        '--site-name' => 'Promo Site',
        '--domain' => 'promo.test',
        '--admin-name' => 'Admin',
        '--admin-email' => 'admin@promo.test',
        '--admin-password' => 'secret-password',
    ])
        ->expectsConfirmation('Building on Laravix? Get a free shout-out — we can feature your site as a reference on laravix.com.', 'yes')
        ->expectsOutputToContain('https://laravix.com/reference')
        ->assertSuccessful();

    Process::assertRan(fn (PendingProcess $process): bool => in_array('https://laravix.com/reference', $process->command, true));
});

test('installer skips the browser open when the promo offer is declined', function () {
    Process::fake();

    $this->artisan('laravix:install', [
        '--site-name' => 'No Promo Site',
        '--domain' => 'no-promo.test',
        '--admin-name' => 'Admin',
        '--admin-email' => 'admin@no-promo.test',
        '--admin-password' => 'secret-password',
    ])
        ->expectsConfirmation('Building on Laravix? Get a free shout-out — we can feature your site as a reference on laravix.com.', 'no')
        ->doesntExpectOutputToContain('https://laravix.com/reference')
        ->assertSuccessful();

    Process::assertNothingRan();
});

test('installer refuses to run on an installed application', function () {
    Site::factory()->create();

    $this->artisan('laravix:install', [
        '--site-name' => 'Another',
        '--domain' => 'another.test',
        '--no-interaction' => true,
    ])->assertFailed();
});
