<?php

use App\Http\Controllers\CmsController;
use App\Http\Controllers\SitemapController;
use Illuminate\Support\Facades\Route;

Route::get('/sitemap.xml', [SitemapController::class, 'index'])->name('sitemap');
Route::get('/robots.txt', [SitemapController::class, 'robots'])->name('robots');

Route::get('/{slug?}', [CmsController::class, 'show'])
    ->where('slug', '.*')
    ->name('cms.show');
