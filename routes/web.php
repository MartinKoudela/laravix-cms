<?php

use App\Http\Controllers\CmsController;
use App\Http\Controllers\InvitationController;
use App\Http\Controllers\SitemapController;
use Illuminate\Support\Facades\Route;

Route::get('/sitemap.xml', [SitemapController::class, 'index'])->name('sitemap');
Route::get('/robots.txt', [SitemapController::class, 'robots'])->name('robots');

Route::get('/invitation/{token}', [InvitationController::class, 'show'])->name('invitation.accept');
Route::post('/invitation/{token}', [InvitationController::class, 'accept'])->name('invitation.accept.submit');

Route::get('/__nav-preview/{token}', [CmsController::class, 'navPreview'])->name('nav.preview');
Route::get('/__appearance-preview/{token}', [CmsController::class, 'appearancePreview'])->name('appearance.preview');

Route::get('/{slug?}', [CmsController::class, 'show'])
    ->where('slug', '.*')
    ->name('cms.show');
