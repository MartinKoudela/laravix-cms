<?php

use Filament\Facades\Filament;
use Laravix\Cms\Enums\ContentStatus;
use Laravix\Cms\Enums\SiteMode;
use Laravix\Cms\Enums\SiteRole;
use Laravix\Cms\Filament\Actions\DuplicateContentAction;
use Laravix\Cms\Filament\Resources\Contents\Pages\EditContent;
use Laravix\Cms\Filament\Resources\Contents\Pages\ListContents;
use Laravix\Cms\Models\Content;
use Laravix\Cms\Models\Site;
use Laravix\Cms\Models\Taxonomy;
use Laravix\Cms\Models\User;
use Livewire\Features\SupportTesting\Testable;
use Livewire\Livewire;

beforeEach(function () {
    $this->site = Site::factory()->create(['domain' => 'localhost', 'theme' => 'default']);
    $this->admin = User::factory()->create(['is_super_admin' => true]);

    $this->content = Content::factory()->for($this->site)->create([
        'created_by' => $this->admin->id,
        'type' => 'page',
        'title' => 'About us',
        'slug' => 'about-us',
        'status' => ContentStatus::PUBLISHED,
        'published_at' => now()->subDay(),
    ]);

    $this->actingAs($this->admin);
    Filament::setCurrentPanel('admin');
    Filament::setTenant($this->site);
});

function listContents(): Testable
{
    return Livewire::test(ListContents::class, ['type' => 'page']);
}

test('the hover bar exposes every quick action for a page', function () {
    listContents()
        ->assertTableActionExists('edit')
        ->assertTableActionExists('builder')
        ->assertTableActionExists('preview')
        ->assertTableActionExists('duplicate')
        ->assertTableActionExists('togglePublish')
        ->assertTableActionExists('reorder')
        ->assertTableActionExists('delete');
});

test('duplicating a page creates a draft copy with its fields and taxonomies', function () {
    $this->content->fields()->create(['key' => 'subtitle', 'value' => 'Hello']);
    $taxonomy = Taxonomy::factory()->for($this->site)->create();
    $this->content->taxonomies()->attach($taxonomy);

    listContents()
        ->callTableAction('duplicate', $this->content)
        ->assertHasNoTableActionErrors();

    $copy = Content::where('slug', 'about-us-copy')->first();

    expect($copy)->not->toBeNull()
        ->and($copy->title)->toBe('About us (copy)')
        ->and($copy->status)->toBe(ContentStatus::DRAFT)
        ->and($copy->published_at)->toBeNull()
        ->and($copy->is_homepage)->toBeFalse()
        ->and($copy->translation_group_id)->toBe($copy->id)
        ->and($copy->fields()->where('key', 'subtitle')->value('value'))->toBe('Hello')
        ->and($copy->taxonomies()->pluck('taxonomies.id')->all())->toBe([$taxonomy->id]);
});

test('duplicate slugs keep counting up, even past soft-deleted copies', function () {
    Content::factory()->for($this->site)->create(['type' => 'page', 'slug' => 'about-us-copy']);
    Content::factory()->for($this->site)->create(['type' => 'page', 'slug' => 'about-us-copy-2'])->delete();

    expect(DuplicateContentAction::uniqueSlug($this->content))->toBe('about-us-copy-3');
});

test('toggle publish flips a published page to draft and back', function () {
    listContents()->callTableAction('togglePublish', $this->content);

    expect($this->content->refresh()->status)->toBe(ContentStatus::DRAFT)
        ->and($this->content->published_at)->toBeNull();

    listContents()->callTableAction('togglePublish', $this->content);

    expect($this->content->refresh()->status)->toBe(ContentStatus::PUBLISHED)
        ->and($this->content->published_at)->not->toBeNull();
});

test('translate shows up only when the site is missing a locale', function () {
    listContents()->assertTableActionHidden('translate', $this->content);

    $this->site->update(['locales' => ['en', 'cs']]);
    Filament::setTenant($this->site->refresh());

    listContents()
        ->callTableAction('translate', $this->content, ['locale' => 'cs'])
        ->assertHasNoTableActionErrors();

    expect(Content::where('translation_group_id', $this->content->translation_group_id)->where('locale', 'cs')->exists())
        ->toBeTrue();
});

test('the builder shortcut is hidden on a headless site', function () {
    $this->site->update(['mode' => SiteMode::HEADLESS]);
    Filament::setTenant($this->site->refresh());

    listContents()->assertTableActionHidden('builder', $this->content);
});

test('an editor cannot see the delete shortcut', function () {
    $editor = User::factory()->create();
    $editor->sites()->attach($this->site, ['role' => SiteRole::EDITOR->value]);
    $this->actingAs($editor);

    listContents()
        ->assertTableActionVisible('edit', $this->content)
        ->assertTableActionHidden('delete', $this->content);
});

test('the edit page header keeps the builder and translate actions after the refactor', function () {
    $this->site->update(['locales' => ['en', 'cs']]);
    Filament::setTenant($this->site->refresh());

    Livewire::test(EditContent::class, ['record' => $this->content->getRouteKey()])
        ->assertActionVisible('builder')
        ->assertActionVisible('translate')
        ->callAction('translate', ['locale' => 'cs'])
        ->assertHasNoActionErrors();

    expect(Content::where('translation_group_id', $this->content->translation_group_id)->where('locale', 'cs')->exists())
        ->toBeTrue();
});
