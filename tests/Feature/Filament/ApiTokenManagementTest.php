<?php

use App\Enums\SiteMode;
use App\Filament\Resources\Settings\Pages\ManageSettings;
use App\Models\Site;
use App\Models\SiteApiToken;
use App\Models\User;
use Filament\Facades\Filament;
use Livewire\Livewire;

beforeEach(function () {
    $this->site = Site::factory()->create([
        'domain' => 'headless.test',
        'mode' => SiteMode::HEADLESS,
    ]);

    $this->admin = User::factory()->create(['is_super_admin' => true]);

    $this->actingAs($this->admin);
    Filament::setCurrentPanel('admin');
    Filament::setTenant($this->site);
});

test('super admin can create an api token', function () {
    Livewire::test(ManageSettings::class, ['group' => 'api'])
        ->callAction('createToken', ['name' => 'Deploy', 'expires_at' => null])
        ->assertHasNoActionErrors();

    $token = SiteApiToken::where('site_id', $this->site->id)->first();

    expect($token)->not->toBeNull()
        ->and($token->name)->toBe('Deploy')
        ->and($token->prefix)->toStartWith('lvx_')
        ->and(strlen($token->token))->toBe(64);
});

test('super admin can revoke an api token', function () {
    $result = SiteApiToken::generateFor($this->site, 'Old frontend');

    Livewire::test(ManageSettings::class, ['group' => 'api'])
        ->callTableAction('delete', $result['token']);

    $this->assertDatabaseMissing('site_api_tokens', [
        'id' => $result['token']->id,
    ]);
});
