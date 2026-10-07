<?php

const UNTOKENIZED_BLOCK_COLORS = [
    'bento.css' => ['#f0fdf4', '#faf5ff'],
    'hero.css' => ['#000'],
    'interactive.css' => ['#fff'],
];

function blockStylesheets(): array
{
    return glob(dirname(__DIR__, 2).'/packages/laravix/cms/resources/css/blocks/*.css');
}

test('block styles take their colors from design tokens', function (string $path) {
    $file = basename($path);

    preg_match_all('/#[0-9a-f]{3,8}\b/i', file_get_contents($path), $matches);

    $hardCoded = array_diff(
        array_map('strtolower', $matches[0]),
        UNTOKENIZED_BLOCK_COLORS[$file] ?? [],
    );

    expect($hardCoded)->toBeEmpty();
})->with(fn () => collect(blockStylesheets())
    ->reject(fn (string $path) => basename($path) === 'tokens.css')
    ->mapWithKeys(fn (string $path) => [basename($path) => [$path]])
    ->all());

test('every token used by the block styles is defined', function () {
    $defined = [];
    $used = [];

    foreach (blockStylesheets() as $path) {
        $css = file_get_contents($path);

        preg_match_all('/(--lx-[a-z0-9-]+)\s*:/', $css, $definitions);
        preg_match_all('/var\((--lx-[a-z0-9-]+)\)/', $css, $usages);

        $defined = [...$defined, ...$definitions[1]];
        $used = [...$used, ...$usages[1]];
    }

    expect(array_diff(array_unique($used), $defined))->toBeEmpty();
});

test('the tokens load before the block styles so themes can override them', function () {
    $imports = file(base_path('packages/laravix/cms/resources/css/blocks.css'), FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

    expect($imports[0])->toBe("@import './blocks/tokens.css';");
});
