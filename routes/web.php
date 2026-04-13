<?php

use App\Http\Controllers\CmsController;
use Illuminate\Support\Facades\Route;

Route::get('/{slug?}', [CmsController::class, 'show'])
    ->where('slug', '.*')
    ->name('cms.show');
