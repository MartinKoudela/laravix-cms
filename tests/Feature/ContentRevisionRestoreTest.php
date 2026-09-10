<?php

use Filament\Facades\Filament;
use Laravix\Cms\Enums\ContentStatus;
use Laravix\Cms\Enums\SiteRole;
use Laravix\Cms\Filament\Resources\Contents\Pages\EditContent;
use Laravix\Cms\Filament\Resources\Contents\RelationManagers\RevisionsRelationManager;
use Laravix\Cms\Models\Content;
use Laravix\Cms\Models\ContentRevision;
use Laravix\Cms\Models\Site;
use Laravix\Cms\Models\User;
use Laravix\Cms\Support\ContentRevisionRestorer;
use Livewire\Livewire;

beforeEach(function () {
    $this->site = Site::factory()->create();
    $this->author = User::factory()->create();
    $this->author->sites()->attach($this->site, ['role' => SiteRole::EDITOR->value]);

    $this->content = Content::factory()->for($this->site)->create([
        'created_by' => $this->author->id,
        'title' => 'Current title',
        'grapesjs_data' => 'current-data',
        'grapesjs_html' => '<p>current html</p>',
    ]);
});

function restore(Content $content, array $data): Content
{
    $revision = $content->revisions()->create([
        'created_by' => $content->created_by,
        'data' => $data,
    ]);

    app(ContentRevisionRestorer::class)->restore($revision);

    return $content->refresh();
}

test('a revision restores the whole snapshot', function () {
    $this->content->fields()->create(['key' => 'subtitle', 'value' => 'current']);

    $restored = restore($this->content, [
        'title' => 'Older title',
        'slug' => 'older-title',
        'status' => ContentStatus::DRAFT->value,
        'is_homepage' => false,
        'published_at' => null,
        'blocks' => [['type' => 'text']],
        'fields' => ['subtitle' => 'older'],
    ]);

    expect($restored->title)->toBe('Older title')
        ->and($restored->slug)->toBe('older-title')
        ->and($restored->blocks)->toBe([['type' => 'text']])
        ->and($restored->fields()->where('key', 'subtitle')->value('value'))->toBe('older');
});

test('a revision never touches the builder canvas', function () {
    $restored = restore($this->content, [
        'title' => 'Older title',
        'slug' => 'older-title',
        'status' => ContentStatus::DRAFT->value,
        'is_homepage' => false,
        'published_at' => null,
        'blocks' => [],
        'fields' => [],
    ]);

    expect($restored->title)->toBe('Older title')
        ->and($restored->grapesjs_data)->toBe('current-data')
        ->and($restored->grapesjs_html)->toBe('<p>current html</p>');
});

test('a partial snapshot only writes back the keys it carries', function () {
    $this->content->fields()->create(['key' => 'subtitle', 'value' => 'keep me']);

    $restored = restore($this->content, ['title' => 'Older title']);

    expect($restored->title)->toBe('Older title')
        ->and($restored->slug)->toBe($this->content->slug)
        ->and($restored->fields()->where('key', 'subtitle')->value('value'))->toBe('keep me');
});

test('saving from the builder writes no revision', function () {
    $this->actingAs($this->author)
        ->post(route('builder.save', [$this->site, $this->content]), [
            'grapesjs_data' => 'new-data',
            'grapesjs_html' => '<p>new html</p>',
        ])
        ->assertOk();

    expect($this->content->refresh()->grapesjs_data)->toBe('new-data')
        ->and(ContentRevision::where('content_id', $this->content->id)->count())->toBe(0);
});

test('legacy builder revisions are hidden from the revisions table', function () {
    $fromBuilder = $this->content->revisions()->create([
        'created_by' => $this->author->id,
        'data' => ['source' => 'builder', 'grapesjs_data' => 'old-canvas'],
    ]);

    $snapshot = $this->content->revisions()->create([
        'created_by' => $this->author->id,
        'data' => ['title' => 'Older title', 'fields' => []],
    ]);

    $admin = User::factory()->create(['is_super_admin' => true]);
    $this->actingAs($admin);
    Filament::setCurrentPanel('admin');
    Filament::setTenant($this->site);

    Livewire::test(RevisionsRelationManager::class, [
        'ownerRecord' => $this->content,
        'pageClass' => EditContent::class,
    ])->assertCanSeeTableRecords([$snapshot])
        ->assertCanNotSeeTableRecords([$fromBuilder]);
});
