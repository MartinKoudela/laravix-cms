<?php

use Filament\Facades\Filament;
use Illuminate\Support\Facades\Cache;
use Laravix\Cms\Enums\SiteRole;
use Laravix\Cms\Filament\Resources\Contents\Pages\EditContent;
use Laravix\Cms\Filament\Resources\Navigation\Pages\ManageNavigation;
use Laravix\Cms\Models\Content;
use Laravix\Cms\Models\Site;
use Laravix\Cms\Models\User;
use Livewire\Livewire;

beforeEach(function () {
    $this->site = Site::factory()->create();

    $this->editor = User::factory()->create();
    $this->editor->sites()->attach($this->site, ['role' => SiteRole::ADMIN->value]);

    $this->actingAs($this->editor);
    Filament::setCurrentPanel('admin');
    Filament::setTenant($this->site);
});

test('the block preview token cannot be derived from the record and user', function () {
    $content = Content::factory()->for($this->site)->create(['created_by' => $this->editor->id]);

    Livewire::test(EditContent::class, ['record' => $content->id]);

    $guessed = md5($content->id.'-'.$this->editor->id.'-block-preview');

    expect(Cache::has("preview_blocks_{$guessed}"))->toBeFalse();
});

test('the nav preview token cannot be derived from the site and user', function () {
    Livewire::test(ManageNavigation::class);

    $guessed = md5($this->site->id.'-'.$this->editor->id.'-nav-preview');

    expect(Cache::has("preview_nav_{$guessed}"))->toBeFalse();
});

test('a guessed block preview token serves no draft to an anonymous visitor', function () {
    $content = Content::factory()->for($this->site)->create(['created_by' => $this->editor->id]);

    Livewire::test(EditContent::class, ['record' => $content->id]);

    $guessed = md5($content->id.'-'.$this->editor->id.'-block-preview');

    auth()->logout();

    $this->get("/__preview/blocks/{$guessed}")
        ->assertOk()
        ->assertSee('spinner');
});

test('the real block preview token still resolves for its own page', function () {
    $content = Content::factory()->for($this->site)->create(['created_by' => $this->editor->id]);

    $token = Livewire::test(EditContent::class, ['record' => $content->id])->get('blockPreviewToken');

    expect($token)->not->toBeEmpty()
        ->and(Cache::get("preview_blocks_{$token}"))->toMatchArray(['content_id' => $content->id]);
});

test('the nav preview token survives a form update', function () {
    $page = Livewire::test(ManageNavigation::class);

    $before = $page->get('previewToken');

    $page->call('refreshPreview');

    expect($page->get('previewToken'))->toBe($before)
        ->and(Cache::has("preview_nav_{$before}"))->toBeTrue();
});
