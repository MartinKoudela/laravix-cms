<?php

use Filament\Facades\Filament;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Laravix\Cms\Enums\SiteRole;
use Laravix\Cms\Filament\Resources\Media\Pages\CreateMedia;
use Laravix\Cms\Models\Media;
use Laravix\Cms\Models\Setting;
use Laravix\Cms\Models\Site;
use Laravix\Cms\Models\User;
use Laravix\Cms\Support\FieldComponentFactory;
use Livewire\Livewire;

beforeEach(function () {
    Storage::fake('public');

    $this->site = Site::factory()->create();
    $this->editor = User::factory()->create();
    $this->editor->sites()->attach($this->site, ['role' => SiteRole::EDITOR->value]);
});

test('the builder rejects an svg upload', function () {
    $this->actingAs($this->editor)
        ->post(route('builder.upload', $this->site), [
            'file' => UploadedFile::fake()->create('logo.svg', 4, 'image/svg+xml'),
        ])
        ->assertInvalid('file');

    expect(Media::count())->toBe(0)
        ->and(Storage::disk('public')->allFiles('media'))->toBeEmpty();
});

test('the builder rejects an svg disguised with an image extension', function () {
    $this->actingAs($this->editor)
        ->post(route('builder.upload', $this->site), [
            'file' => UploadedFile::fake()->create('logo.png', 4, 'image/svg+xml'),
        ])
        ->assertInvalid('file');

    expect(Media::count())->toBe(0);
});

test('the builder still accepts a bitmap upload', function () {
    $this->actingAs($this->editor)
        ->post(route('builder.upload', $this->site), [
            'file' => UploadedFile::fake()->image('hero.png'),
        ])
        ->assertOk();

    expect(Media::where('site_id', $this->site->id)->count())->toBe(1);
});

function acceptedTypesFor(string $field): array
{
    $admin = User::factory()->create(['is_super_admin' => true]);

    test()->actingAs($admin);
    Filament::setCurrentPanel('admin');
    Filament::setTenant(test()->site);

    $form = Livewire::test(CreateMedia::class)->instance()->getSchema('form');

    if ($field === 'media form') {
        return $form->getComponent('path')->getAcceptedFileTypes();
    }

    return FieldComponentFactory::mediaSelect('img', 'Image')
        ->getCreateOptionActionForm($form)[0]
        ->getAcceptedFileTypes();
}

dataset('filament uploads', ['media form', 'media picker']);

test('the filament uploads do not accept svg', function (string $field) {
    expect(Str::is(acceptedTypesFor($field), 'image/svg+xml'))->toBeFalse();
})->with('filament uploads');

test('the filament uploads list no wildcard types', function (string $field) {
    $types = acceptedTypesFor($field);

    expect($types)->not->toBeEmpty();

    foreach ($types as $type) {
        expect($type)->not->toContain('*');
    }
})->with('filament uploads');

test('the builder accepts an svg once the site opts in', function () {
    Setting::create(['site_id' => $this->site->id, 'key' => 'allow_svg_uploads', 'value' => '1']);

    $this->actingAs($this->editor)
        ->post(route('builder.upload', $this->site), [
            'file' => UploadedFile::fake()->create('logo.svg', 4, 'image/svg+xml'),
        ])
        ->assertOk();

    expect(Media::where('site_id', $this->site->id)->count())->toBe(1);
});

test('the filament uploads accept svg once the site opts in', function (string $field) {
    Setting::create(['site_id' => $this->site->id, 'key' => 'allow_svg_uploads', 'value' => '1']);

    expect(acceptedTypesFor($field))->toContain('image/svg+xml');
})->with('filament uploads');
