<?php

use Filament\Facades\Filament;
use Laravix\Cms\Enums\ContentStatus;
use Laravix\Cms\Enums\SiteRole;
use Laravix\Cms\Filament\Resources\Contents\Pages\CreateContent;
use Laravix\Cms\Filament\Resources\Contents\Pages\EditContent;
use Laravix\Cms\Filament\Resources\Contents\Pages\ListContents;
use Laravix\Cms\Filament\Resources\Navigation\Pages\ManageNavigation;
use Laravix\Cms\Filament\Resources\Taxonomies\Pages\EditTaxonomy;
use Laravix\Cms\Filament\Widgets\LatestContentWidget;
use Laravix\Cms\Filament\Widgets\StatsOverviewWidget;
use Laravix\Cms\Models\Content;
use Laravix\Cms\Models\Site;
use Laravix\Cms\Models\Taxonomy;
use Laravix\Cms\Models\User;
use Livewire\Livewire;

beforeEach(function () {
    $this->site = Site::factory()->create(['domain' => 'localhost', 'theme' => 'default']);
    $this->admin = User::factory()->create();
    $this->admin->sites()->attach($this->site, ['role' => SiteRole::ADMIN->value]);

    $this->actingAs($this->admin);
    Filament::setCurrentPanel('admin');
    Filament::setTenant($this->site);
});

function actionNames(array $actions): array
{
    return array_map(fn ($action) => $action->getName(), $actions);
}

test('edit pages offer save in the header and delete at the bottom', function () {
    $content = Content::factory()->for($this->site)->create(['created_by' => $this->admin->id]);

    $page = Livewire::test(EditContent::class, ['record' => $content->id])->instance();

    expect(actionNames($page->getCachedHeaderActions()))->toContain('saveFromHeader')
        ->not->toContain('delete')
        ->and(actionNames(invade($page)->getFormActions()))->toContain('save', 'cancel', 'delete');
});

test('the header save actually saves the record', function () {
    $content = Content::factory()->for($this->site)->create(['created_by' => $this->admin->id, 'type' => 'page', 'title' => 'Before']);

    Livewire::test(EditContent::class, ['record' => $content->id])
        ->fillForm(['title' => 'After'])
        ->callAction('saveFromHeader')
        ->assertHasNoErrors();

    expect($content->refresh()->title)->toBe('After');
});

test('the layout applies to every edit page through the base class', function () {
    $taxonomy = Taxonomy::factory()->for($this->site)->create();

    $page = Livewire::test(EditTaxonomy::class, ['record' => $taxonomy->id])->instance();

    expect(actionNames($page->getCachedHeaderActions()))->toContain('saveFromHeader')
        ->and(actionNames(invade($page)->getFormActions()))->toContain('delete');
});

test('create pages offer create in the header', function () {
    $page = Livewire::test(CreateContent::class)->instance();

    expect(actionNames($page->getCachedHeaderActions()))->toContain('createFromHeader');
});

test('the builder is a header action, not a tab', function () {
    $content = Content::factory()->for($this->site)->create(['created_by' => $this->admin->id, 'type' => 'page']);

    Livewire::test(EditContent::class, ['record' => $content->id])->assertActionVisible('builder');

    expect(view()->exists('laravix::filament.partials.block-builder'))->toBeFalse();
});

test('preview is hidden while the content is a draft', function () {
    $draft = Content::factory()->for($this->site)->create(['created_by' => $this->admin->id, 'status' => ContentStatus::DRAFT]);
    $live = Content::factory()->for($this->site)->create(['created_by' => $this->admin->id, 'status' => ContentStatus::PUBLISHED, 'published_at' => now()->subDay()]);

    Livewire::test(EditContent::class, ['record' => $draft->id])->assertActionHidden('preview');
    Livewire::test(EditContent::class, ['record' => $live->id])->assertActionVisible('preview');
});

test('the content list no longer shows the site column', function () {
    Livewire::test(ListContents::class)->assertTableColumnDoesNotExist('site.name');
});

test('the dashboard only counts the current site', function () {
    $other = Site::factory()->create();
    Content::factory()->for($this->site)->count(2)->create(['created_by' => $this->admin->id, 'status' => ContentStatus::PUBLISHED]);
    Content::factory()->for($other)->count(5)->create(['created_by' => $this->admin->id, 'status' => ContentStatus::PUBLISHED]);

    $stats = invade(Livewire::test(StatsOverviewWidget::class)->instance())->getStats();
    $published = collect($stats)->first(fn ($stat) => $stat->getLabel() === __('laravix::content.stats.published'));

    expect($published->getValue())->toBe(2)
        ->and(collect($stats)->map->getLabel())->not->toContain(__('laravix::sites.stats.title'));
});

test('the dashboard never lists content from another site', function () {
    $other = Site::factory()->create();
    $mine = Content::factory()->for($this->site)->create(['created_by' => $this->admin->id, 'title' => 'Mine']);
    $theirs = Content::factory()->for($other)->create(['created_by' => $this->admin->id, 'title' => 'Theirs']);

    Livewire::test(LatestContentWidget::class)
        ->assertCanSeeTableRecords([$mine])
        ->assertCanNotSeeTableRecords([$theirs]);
});

test('the nav preview renders even when the site has no content', function () {
    $token = Livewire::test(ManageNavigation::class)->get('previewToken');

    $this->get("/__preview/nav/{$token}?part=header")->assertOk();
});
