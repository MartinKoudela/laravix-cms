<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('changelog_releases', 'translations')) {
            Schema::table('changelog_releases', function (Blueprint $table) {
                $table->json('translations')->nullable()->after('title');
            });
        }

        if (! Schema::hasColumn('changelog_items', 'translations')) {
            Schema::table('changelog_items', function (Blueprint $table) {
                $table->json('translations')->nullable()->after('text');
            });
        }
    }

    public function down(): void
    {
        Schema::table('changelog_releases', function (Blueprint $table) {
            $table->dropColumn('translations');
        });

        Schema::table('changelog_items', function (Blueprint $table) {
            $table->dropColumn('translations');
        });
    }
};
