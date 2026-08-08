<?php

use Laravix\Cms\Enums\SiteMode;
use Laravix\Cms\Models\Setting;
use Laravix\Cms\Models\Site;
use Laravix\Cms\Models\SiteApiToken;
use Laravix\Cms\Models\Taxonomy;
use Laravix\Cms\Models\User;
use Laravix\Cms\Support\SettingRegistry;
use Laravix\Cms\Support\TaxonomyTypeRegistry;

test('tiktok url is a registered social setting', function () {
    $keys = collect(SettingRegistry::all())->map(fn ($definition) => $definition->key);

    expect($keys)->toContain('tiktok_url');
});

test('tiktok url is exposed by the public settings endpoint', function () {
    $site = Site::factory()->create([
        'domain' => 'headless.test',
        'mode' => SiteMode::HEADLESS,
    ]);

    Setting::create([
        'site_id' => $site->id,
        'key' => 'tiktok_url',
        'value' => 'https://tiktok.com/@laravix',
    ]);

    $result = SiteApiToken::generateFor($site, 'Settings');

    $this->getJson('/api/v1/settings', [
        'X-Site-Domain' => 'headless.test',
        'Authorization' => 'Bearer '.$result['plaintext'],
    ])
        ->assertSuccessful()
        ->assertJsonFragment(['tiktok_url' => 'https://tiktok.com/@laravix']);
});

test('taxonomy type filter options come from the registry', function () {
    TaxonomyTypeRegistry::register('docs_section', 'laravix::taxonomy.types.category');

    expect(TaxonomyTypeRegistry::options())->toHaveKey('docs_section');
});

test('taxonomies are ordered by sort order', function () {
    $site = Site::factory()->create();

    $second = Taxonomy::factory()->create(['site_id' => $site->id, 'sort_order' => 2]);
    $first = Taxonomy::factory()->create(['site_id' => $site->id, 'sort_order' => 1]);

    $ordered = Taxonomy::query()
        ->where('site_id', $site->id)
        ->orderBy('sort_order')
        ->pluck('id');

    expect($ordered->all())->toBe([$first->id, $second->id]);
});

test('taxonomy sort order survives a fillable update', function () {
    $taxonomy = Taxonomy::factory()->create([
        'site_id' => Site::factory()->create()->id,
        'sort_order' => 0,
    ]);

    $taxonomy->update(['sort_order' => 7]);

    expect($taxonomy->fresh()->sort_order)->toBe(7);
});

test('admin taxonomies table renders without raw translation keys', function () {
    $site = Site::factory()->create(['domain' => 'localhost', 'theme' => 'default']);
    $admin = User::factory()->create(['is_super_admin' => true]);

    Taxonomy::factory()->create(['site_id' => $site->id]);

    $html = $this->actingAs($admin)
        ->get('/admin/'.$site->id.'/taxonomies?locale=cs')
        ->assertSuccessful()
        ->getContent();

    preg_match_all('/laravix::[a-zA-Z0-9_.]+/', $html, $matches);

    expect(array_unique($matches[0]))->toBe([]);
});
