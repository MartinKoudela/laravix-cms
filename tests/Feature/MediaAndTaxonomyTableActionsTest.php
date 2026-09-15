<?php

use Filament\Facades\Filament;
use Laravix\Cms\Enums\SiteRole;
use Laravix\Cms\Filament\Actions\ShowTaxonomyContentsAction;
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

test('the media hover bar offers edit, open, copy url and delete', function () {
    $media = Media::factory()->for($this->site)->create(['created_by' => $this->admin->id, 'mime_type' => 'application/pdf']);

    Livewire::test(ListMedia::class)
        ->assertTableActionVisible('edit', $media)
        ->assertTableActionVisible('open', $media)
        ->assertTableActionVisible('copyUrl', $media)
        ->assertTableActionVisible('delete', $media)
        ->assertTableActionHasUrl('open', $media->url, $media);
});

test('an editor cannot delete media from the hover bar', function () {
    $media = Media::factory()->for($this->site)->create(['created_by' => $this->admin->id, 'mime_type' => 'application/pdf']);
    $editor = User::factory()->create();
    $editor->sites()->attach($this->site, ['role' => SiteRole::EDITOR->value]);
    $this->actingAs($editor);

    Livewire::test(ListMedia::class)
        ->assertTableActionVisible('edit', $media)
        ->assertTableActionHidden('delete', $media);
});

test('the taxonomy hover bar links to the contents filtered by that taxonomy', function () {
    $taxonomy = Taxonomy::factory()->for($this->site)->create(['type' => 'category']);
    $post = Content::factory()->for($this->site)->create(['type' => 'post', 'created_by' => $this->admin->id]);
    $post->taxonomies()->attach($taxonomy);

    expect(ShowTaxonomyContentsAction::contentTypeFor($taxonomy)?->key)->toBe('post');

    Livewire::test(ListTaxonomies::class)
        ->assertTableActionVisible('showContents', $taxonomy)
        ->assertTableActionVisible('reorder', $taxonomy)
        ->assertTableActionVisible('delete', $taxonomy)
        ->assertTableActionHasUrl(
            'showContents',
            'http://localhost/admin/'.$this->site->id.'/contents?type=post&taxonomy='.$taxonomy->id,
            $taxonomy,
        );
});

test('the taxonomy query parameter pre-selects the taxonomy filter on the content list', function () {
    $taxonomy = Taxonomy::factory()->for($this->site)->create(['type' => 'category']);
    $tagged = Content::factory()->for($this->site)->create(['type' => 'post', 'created_by' => $this->admin->id]);
    $other = Content::factory()->for($this->site)->create(['type' => 'post', 'created_by' => $this->admin->id]);
    $tagged->taxonomies()->attach($taxonomy);

    Livewire::withQueryParams(['type' => 'post', 'taxonomy' => $taxonomy->id])
        ->test(ListContents::class)
        ->assertCanSeeTableRecords([$tagged])
        ->assertCanNotSeeTableRecords([$other]);
});

test('an empty taxonomy falls back to the first content type that accepts it', function () {
    $taxonomy = Taxonomy::factory()->for($this->site)->create(['type' => 'tag']);

    expect(ShowTaxonomyContentsAction::contentTypeFor($taxonomy)?->key)->toBe('page');
});
