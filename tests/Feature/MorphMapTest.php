<?php

use Illuminate\Support\Facades\DB;
use Laravix\Cms\Enums\ContentStatus;
use Laravix\Cms\Models\Content;
use Laravix\Cms\Models\Site;
use Laravix\Cms\Models\User;

function makeContent(): Content
{
    $site = Site::factory()->create();
    $user = User::factory()->create();

    return Content::factory()->create([
        'site_id' => $site->id,
        'created_by' => $user->id,
        'type' => 'page',
        'status' => ContentStatus::PUBLISHED,
        'published_at' => now()->subDay(),
    ]);
}

test('activity log stores morph aliases instead of class names', function () {
    $content = makeContent();

    $activity = DB::table('activity_log')
        ->where('subject_id', $content->id)
        ->orderByDesc('id')
        ->first();

    expect($activity)->not->toBeNull()
        ->and($activity->subject_type)->toBe('content');
});

test('recycle bin stores morph aliases instead of class names', function () {
    $content = makeContent();

    $content->delete();

    $item = DB::table('recycle_bin_items')
        ->where('model_id', $content->id)
        ->orderByDesc('id')
        ->first();

    expect($item)->not->toBeNull()
        ->and($item->model_type)->toBe('content');
});

test('morph alias migration rewrites legacy class names', function () {
    DB::table('activity_log')->insert([
        'log_name' => 'site-1',
        'description' => 'updated',
        'subject_type' => 'App\Models\Content',
        'subject_id' => 999,
        'causer_type' => 'App\Models\User',
        'causer_id' => 999,
        'properties' => '{}',
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    DB::table('recycle_bin_items')->insert([
        'model_type' => 'App\Models\Taxonomy',
        'model_id' => 999,
        'state' => '{}',
        'deleted_at' => now(),
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    $migration = require database_path('migrations/2026_07_11_093725_convert_morph_types_to_aliases.php');
    $migration->up();

    $activity = DB::table('activity_log')->where('subject_id', 999)->first();
    $item = DB::table('recycle_bin_items')->where('model_id', 999)->first();

    expect($activity->subject_type)->toBe('content')
        ->and($activity->causer_type)->toBe('user')
        ->and($item->model_type)->toBe('taxonomy');
});
