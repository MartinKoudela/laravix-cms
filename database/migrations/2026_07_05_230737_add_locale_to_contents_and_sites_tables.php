<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('sites', 'locales')) {
            Schema::table('sites', function (Blueprint $table) {
                $table->json('locales')->nullable()->after('theme');
            });
        }

        if (! Schema::hasColumn('contents', 'locale')) {
            Schema::table('contents', function (Blueprint $table) {
                $table->string('locale', 12)->nullable()->after('type');
            });
        }

        if (! Schema::hasColumn('contents', 'translation_group_id')) {
            Schema::table('contents', function (Blueprint $table) {
                $table->unsignedBigInteger('translation_group_id')->nullable()->after('locale');
            });
        }

        if (! Schema::hasIndex('contents', 'contents_translation_group_id_index')) {
            Schema::table('contents', function (Blueprint $table) {
                $table->index('translation_group_id');
            });
        }

        foreach (DB::table('sites')->pluck('id') as $siteId) {
            $locale = DB::table('settings')
                ->where('site_id', $siteId)
                ->where('key', 'locale')
                ->value('value') ?: 'en';

            DB::table('contents')
                ->where('site_id', $siteId)
                ->whereNull('locale')
                ->update(['locale' => $locale]);
        }

        DB::table('contents')->whereNull('translation_group_id')->update(['translation_group_id' => DB::raw('id')]);

        if (! Schema::hasIndex('contents', 'contents_site_id_slug_locale_unique')) {
            Schema::table('contents', function (Blueprint $table) {
                $table->unique(['site_id', 'slug', 'locale']);
            });
        }

        if (Schema::hasIndex('contents', 'contents_site_id_slug_unique')) {
            Schema::table('contents', function (Blueprint $table) {
                $table->dropUnique(['site_id', 'slug']);
            });
        }

        if (! Schema::hasIndex('contents', 'contents_translation_group_id_locale_unique')) {
            Schema::table('contents', function (Blueprint $table) {
                $table->unique(['translation_group_id', 'locale']);
            });
        }
    }

    public function down(): void
    {
        Schema::table('contents', function (Blueprint $table) {
            $table->unique(['site_id', 'slug']);
        });

        Schema::table('contents', function (Blueprint $table) {
            $table->dropUnique(['site_id', 'slug', 'locale']);
            $table->dropUnique(['translation_group_id', 'locale']);
            $table->dropIndex(['translation_group_id']);
            $table->dropColumn(['locale', 'translation_group_id']);
        });

        Schema::table('sites', function (Blueprint $table) {
            $table->dropColumn('locales');
        });
    }
};
