<?php

use Filament\Facades\Filament;

test('admin footer links render literal labels instead of translation keys', function () {
    $links = Filament::getPanel('admin')->getPlugin('filament-easy-footer')->getLinks();

    expect($links)->toBe([
        ['title' => 'Website', 'url' => 'https://laravix.com'],
        ['title' => 'Docs', 'url' => 'https://laravix.com/docs'],
        ['title' => 'Contact', 'url' => 'https://laravix.com/contact'],
    ]);
});
