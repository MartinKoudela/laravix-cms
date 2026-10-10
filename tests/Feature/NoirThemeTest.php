<?php

use Illuminate\Support\Facades\Cache;
use Laravix\Cms\Enums\ContentStatus;
use Laravix\Cms\Enums\SiteMode;
use Laravix\Cms\Models\Content;
use Laravix\Cms\Models\Setting;
use Laravix\Cms\Models\Site;
use Laravix\Cms\Models\User;

beforeEach(function () {
    $this->site = Site::factory()->create([
        'name' => 'Noir Studio',
        'domain' => 'localhost',
        'theme' => 'noir',
        'mode' => SiteMode::THEME,
        'navigations' => [
            'header' => [
                ['label' => 'Work', 'url' => '/work'],
                ['label' => 'About', 'url' => '/about', 'children' => [
                    ['label' => 'Team', 'url' => '/team'],
                ]],
            ],
            'footer' => [
                ['label' => 'Imprint', 'url' => '/imprint'],
            ],
        ],
    ]);

    $this->author = User::factory()->create();
});

function noirContent(Site $site, User $author, array $attributes = []): Content
{
    return Content::factory()->for($site)->create(array_merge([
        'created_by' => $author->id,
        'type' => 'page',
        'title' => 'Selected work',
        'slug' => 'work',
        'status' => ContentStatus::PUBLISHED,
        'published_at' => now()->subDay(),
    ], $attributes));
}

test('noir renders pages inside its own layout, header and footer', function () {
    noirContent($this->site, $this->author, ['grapesjs_html' => '<section class="lx-section">NOIR BODY</section>']);
    Setting::create(['site_id' => $this->site->id, 'key' => 'instagram_url', 'value' => 'https://instagram.com/noir']);

    $this->get('/work')
        ->assertOk()
        ->assertSee('<body id="top" class="noir">', false)
        ->assertSee('/themes/noir/app.css', false)
        ->assertSee('NOIR BODY')
        ->assertSeeInOrder(['noir-header', 'Work', 'About', 'Team', 'noir-footer', 'Imprint'], false)
        ->assertSee('class="noir-header__link nav-link is-active"', false)
        ->assertSee('https://instagram.com/noir', false)
        ->assertDontSee('bg-white text-gray-900', false);
});

test('noir uses its own templates for posts and archives', function (string $type, string $marker) {
    noirContent($this->site, $this->author, ['type' => $type, 'slug' => 'journal', 'title' => 'Journal']);

    $this->get('/journal')
        ->assertOk()
        ->assertSee($marker, false)
        ->assertSee('Journal');
})->with([
    'post' => ['post', 'noir-article__title'],
    'archive' => ['archive', 'noir-archive'],
    'page without builder content' => ['page', 'noir-page__title'],
]);

test('navigation design set by the editor overrides the noir defaults', function () {
    $this->site->update(['nav_design' => [
        'header' => ['bg_color' => '#ff0000', 'bg_opacity' => 50, 'text_color' => null, 'height' => 90],
        'footer' => ['text_color' => '#00ff00', 'show_copyright' => false],
    ]]);
    noirContent($this->site, $this->author);

    $this->get('/work')
        ->assertOk()
        ->assertSee('--noir-header-bg:rgba(255,0,0,0.5)', false)
        ->assertSee('--noir-header-height:90px', false)
        ->assertDontSee('--noir-header-text', false)
        ->assertSee('--noir-footer-text:#00ff00', false)
        ->assertDontSee('noir-footer__copyright', false);
});

test('the navigation editor previews the noir header and footer', function (string $part, string $marker) {
    Cache::put('preview_nav_noir-test', [
        'site_id' => $this->site->id,
        'navigations' => $this->site->navigations,
        'nav_design' => [],
    ]);

    $this->get("/__preview/nav/noir-test?part={$part}")
        ->assertOk()
        ->assertSee($marker, false);
})->with([
    'header' => ['header', 'noir-header__nav'],
    'footer' => ['footer', 'noir-footer__nav'],
]);
