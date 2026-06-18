<?php

use App\Http\Controllers\BuilderController;
use App\Http\Controllers\CmsController;
use App\Http\Controllers\InvitationController;
use App\Http\Controllers\PreviewController;
use App\Http\Controllers\SitemapController;
use App\Http\Controllers\ThemePreviewController;
use BezhanSalleh\LanguageSwitch\Http\Middleware\SwitchLanguageLocale;
use Illuminate\Support\Facades\Route;

Route::get('/sitemap.xml', [SitemapController::class, 'index'])->name('sitemap');
Route::get('/robots.txt', [SitemapController::class, 'robots'])->name('robots');

Route::get('/invitation/{token}', [InvitationController::class, 'show'])->name('invitation.accept');
Route::post('/invitation/{token}', [InvitationController::class, 'accept'])->name('invitation.accept.submit');

Route::prefix('/__preview')->group(function () {
    Route::get('/nav/{token}', [PreviewController::class, 'nav'])->name('nav.preview');
    Route::get('/blocks/{token}', [PreviewController::class, 'blocks'])->name('block.preview');
    Route::get('/theme/{theme}', [ThemePreviewController::class, 'show'])->name('theme.preview');
});

Route::middleware(['auth', SwitchLanguageLocale::class])->group(function () {
    Route::get('/admin/{site}/contents/{content}/builder', [BuilderController::class, 'edit'])
        ->name('builder.edit');
    Route::post('/admin/{site}/contents/{content}/builder', [BuilderController::class, 'save'])
        ->name('builder.save');
    Route::post('/admin/{site}/builder/upload', [BuilderController::class, 'upload'])
        ->name('builder.upload');
});

Route::post('/builder/{site}/contact', [BuilderController::class, 'contact'])
    ->name('builder.contact');

Route::get('/{slug?}', [CmsController::class, 'show'])
    ->where('slug', '.*')
    ->name('cms.show');
