<?php

use Filament\Facades\Filament;
use Laravix\Cms\Enums\TableLayout;
use Laravix\Cms\Filament\Resources\Contents\Pages\ListContents;
use Laravix\Cms\Filament\Resources\Media\Pages\ListMedia;
use Laravix\Cms\Filament\Resources\Taxonomies\Pages\ListTaxonomies;
use Laravix\Cms\Models\Content;
use Laravix\Cms\Models\Media;
use Laravix\Cms\Models\Site;
use Laravix\Cms\Models\Taxonomy;
use Laravix\Cms\Models\User;
use Livewire\Livewire;

beforeEach(function () {
    $this->site = Site::factory()->create(['domain' => 'localhost', 'theme' => 'default']);
    $this->admin = User::factory()->create(['is_super_admin' => true]);

    $this->actingAs($this->admin);
    Filament::setCurrentPanel('admin');
    Filament::setTenant($this->site);
});

test('media opens as a grid while contents and taxonomies open as a list', function () {
    expect(Livewire::test(ListMedia::class)->instance()->getTableLayout())->toBe(TableLayout::Grid)
        ->and(Livewire::test(ListContents::class, ['type' => 'page'])->instance()->getTableLayout())->toBe(TableLayout::List)
        ->and(Livewire::test(ListTaxonomies::class)->instance()->getTableLayout())->toBe(TableLayout::List);
});

test('the toolbar action flips the layout and the choice survives a reload', function () {
    $media = Media::factory()->for($this->site)->create(['created_by' => $this->admin->id, 'mime_type' => 'application/pdf']);

    $page = Livewire::test(ListMedia::class)
        ->assertCanSeeTableRecords([$media])
        ->callTableAction('switchLayout')
        ->assertHasNoTableActionErrors();

    expect($page->instance()->getTableLayout())->toBe(TableLayout::List)
        ->and($page->instance()->getTable()->getContentGrid())->toBeNull();

    expect(Livewire::test(ListMedia::class)->instance()->getTableLayout())->toBe(TableLayout::List);
});

test('each page remembers its own layout', function () {
    Livewire::test(ListContents::class, ['type' => 'page'])->callTableAction('switchLayout');

    expect(Livewire::test(ListContents::class, ['type' => 'page'])->instance()->getTableLayout())->toBe(TableLayout::Grid)
        ->and(Livewire::test(ListTaxonomies::class)->instance()->getTableLayout())->toBe(TableLayout::List);
});

test('grid cards render the records with their hover actions', function () {
    $content = Content::factory()->for($this->site)->create(['type' => 'page', 'title' => 'Grid card page', 'created_by' => $this->admin->id]);
    $taxonomy = Taxonomy::factory()->for($this->site)->create(['name' => 'Grid taxonomy']);
    $content->taxonomies()->attach($taxonomy);

    Livewire::test(ListContents::class, ['type' => 'page'])
        ->callTableAction('switchLayout')
        ->assertCanSeeTableRecords([$content])
        ->assertSee('Grid card page')
        ->assertTableActionVisible('duplicate', $content);

    Livewire::test(ListTaxonomies::class)
        ->callTableAction('switchLayout')
        ->assertCanSeeTableRecords([$taxonomy])
        ->assertSee('Grid taxonomy')
        ->assertSee(trans_choice('laravix::taxonomy.contents_count', 1, ['count' => 1]));
});
