<?php

use Filament\Facades\Filament;
use Laravix\Cms\Filament\Actions\DuplicateContentTypeFieldAction;
use Laravix\Cms\Filament\Resources\ContentTypeFields\Pages\ListContentTypeFields;
use Laravix\Cms\Filament\Resources\CustomCodeBlocks\Pages\ListCustomCodeBlocks;
use Laravix\Cms\Models\ContentTypeField;
use Laravix\Cms\Models\CustomCodeBlock;
use Laravix\Cms\Models\Site;
use Laravix\Cms\Models\User;
use Livewire\Livewire;

beforeEach(function () {
    $this->site = Site::factory()->create(['domain' => 'localhost', 'theme' => 'default']);
    $this->admin = User::factory()->create(['is_super_admin' => true]);

    $this->actingAs($this->admin);
    Filament::setCurrentPanel('admin');
    Filament::setTenant($this->site);
});

test('duplicating a custom field copies it under a free key', function () {
    $field = ContentTypeField::factory()->for($this->site)->create([
        'content_type' => 'page',
        'key' => 'subtitle',
        'label' => 'Subtitle',
        'sort_order' => 3,
    ]);

    Livewire::test(ListContentTypeFields::class)
        ->assertTableActionVisible('reorder', $field)
        ->assertTableActionVisible('delete', $field)
        ->callTableAction('duplicate', $field)
        ->assertHasNoTableActionErrors();

    $copy = ContentTypeField::where('key', 'subtitle_copy')->first();

    expect($copy)->not->toBeNull()
        ->and($copy->label)->toBe('Subtitle (copy)')
        ->and($copy->content_type)->toBe('page')
        ->and($copy->sort_order)->toBe(4)
        ->and(DuplicateContentTypeFieldAction::uniqueKey($field))->toBe('subtitle_copy_2');
});

test('duplicating a custom code block copies its code', function () {
    $block = CustomCodeBlock::create([
        'site_id' => $this->site->id,
        'name' => 'Promo banner',
        'icon' => 'star',
        'html_content' => '<div>hi</div>',
        'css_content' => 'div{}',
        'js_content' => 'console.log(1)',
    ]);

    Livewire::test(ListCustomCodeBlocks::class)
        ->assertTableActionVisible('delete', $block)
        ->callTableAction('duplicate', $block)
        ->assertHasNoTableActionErrors();

    $copy = CustomCodeBlock::where('name', 'Promo banner (copy)')->first();

    expect($copy)->not->toBeNull()
        ->and($copy->html_content)->toBe('<div>hi</div>')
        ->and($copy->js_content)->toBe('console.log(1)')
        ->and($copy->icon)->toBe('star');
});
