<?php

use Laravix\Cms\Models\Site;

test('error pages render translated text instead of raw keys', function (string $view, string $expected) {
    Site::factory()->create(['domain' => 'localhost', 'theme' => 'default']);

    $html = view($view)->render();

    expect($html)->toContain(e($expected))
        ->and($html)->toContain('Back to homepage')
        ->and($html)->not->toContain('common.errors.')
        ->and($html)->not->toContain('common.back_home');
})->with([
    'errors.404' => ['errors.404', 'Page not found'],
    'errors.500' => ['errors.500', 'Something went wrong'],
    'errors.503' => ['errors.503', 'We\'ll be right back'],
]);

test('missing page returns the 404 view without raw translation keys', function () {
    Site::factory()->create(['domain' => 'localhost', 'theme' => 'default']);

    $html = $this->get('/this-page-does-not-exist')
        ->assertNotFound()
        ->getContent();

    expect($html)->toContain('Page not found')
        ->and($html)->not->toContain('common.errors.');
});
