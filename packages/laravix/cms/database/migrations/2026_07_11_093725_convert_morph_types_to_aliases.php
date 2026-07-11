<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    private const ALIASES = [
        'App\Models\Content' => 'content',
        'App\Models\ContentField' => 'content_field',
        'App\Models\ContentRevision' => 'content_revision',
        'App\Models\ContentTypeField' => 'content_type_field',
        'App\Models\CustomCodeBlock' => 'custom_code_block',
        'App\Models\Media' => 'media',
        'App\Models\Redirect' => 'redirect',
        'App\Models\Setting' => 'setting',
        'App\Models\Site' => 'site',
        'App\Models\SiteApiToken' => 'site_api_token',
        'App\Models\SiteUser' => 'site_user',
        'App\Models\Taxonomy' => 'taxonomy',
        'App\Models\User' => 'user',
        'App\Models\UserInvitation' => 'user_invitation',
    ];

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        foreach (self::ALIASES as $class => $alias) {
            if (Schema::hasTable('activity_log')) {
                DB::table('activity_log')->where('subject_type', $class)->update(['subject_type' => $alias]);
                DB::table('activity_log')->where('causer_type', $class)->update(['causer_type' => $alias]);
            }

            if (Schema::hasTable('recycle_bin_items')) {
                DB::table('recycle_bin_items')->where('model_type', $class)->update(['model_type' => $alias]);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        foreach (self::ALIASES as $class => $alias) {
            if (Schema::hasTable('activity_log')) {
                DB::table('activity_log')->where('subject_type', $alias)->update(['subject_type' => $class]);
                DB::table('activity_log')->where('causer_type', $alias)->update(['causer_type' => $class]);
            }

            if (Schema::hasTable('recycle_bin_items')) {
                DB::table('recycle_bin_items')->where('model_type', $alias)->update(['model_type' => $class]);
            }
        }
    }
};
