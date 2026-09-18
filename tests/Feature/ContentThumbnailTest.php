<?php

use Filament\Facades\Filament;
use Laravix\Cms\Enums\ContentStatus;
use Laravix\Cms\Enums\SiteMode;
use Laravix\Cms\Enums\SiteRole;
use Laravix\Cms\Filament\Resources\Contents\Pages\ListContents;
use Laravix\Cms\Models\Content;
use Laravix\Cms\Models\Site;
use Laravix\Cms\Models\User;
use Livewire\Livewire;

beforeEach(function () {
    $this->site = Site::factory()->create(['domain' => 'localhost', 'theme' => 'default']);
    $this->admin = User::factory()->create(['is_super_admin' => true]);

    $this->draft = Content::factory()->for($this->site)->create([
        'created_by' => $this->admin->id,
        'type' => 'page',
        'title' => 'Secret draft',
        'status' => ContentStatus::DRAFT,
    ]);
});

test('a thumbnail renders drafts for users who may view the content', function () {
    $this->actingAs($this->admin)
        ->get(route('content.thumbnail', $this->draft))
        ->assertOk()
        ->assertSee('Secret draft');
});

test('a thumbnail is not served to guests or to users of other sites', function () {
    $this->getJson(route('content.thumbnail', $this->draft))->assertUnauthorized();

    $outsider = User::factory()->create();
    $outsider->sites()->attach(Site::factory()->create(), ['role' => SiteRole::ADMIN->value]);

    $this->actingAs($outsider)
        ->get(route('content.thumbnail', $this->draft))
        ->assertForbidden();
});

test('a headless site has no thumbnail to render', function () {
    $this->site->update(['mode' => SiteMode::HEADLESS]);

    $this->actingAs($this->admin)
        ->get(route('content.thumbnail', $this->draft))
        ->assertNotFound();
});

test('grid cards embed the thumbnail frame', function () {
    $this->actingAs($this->admin);
    Filament::setCurrentPanel('admin');
    Filament::setTenant($this->site);

    Livewire::test(ListContents::class, ['type' => 'page'])
        ->callTableAction('switchLayout')
        ->assertSeeHtml('class="lx-page-thumb"')
        ->assertSeeHtml(route('content.thumbnail', $this->draft));
});
