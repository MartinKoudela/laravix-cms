<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('contents', 'sort_order')) {
            Schema::table('contents', function (Blueprint $table) {
                $table->unsignedInteger('sort_order')->default(0)->after('is_homepage');
            });
        }

        if (! Schema::hasColumn('taxonomies', 'sort_order')) {
            Schema::table('taxonomies', function (Blueprint $table) {
                $table->unsignedInteger('sort_order')->default(0)->after('slug');
            });
        }
    }

    public function down(): void
    {
        Schema::table('contents', function (Blueprint $table) {
            $table->dropColumn('sort_order');
        });

        Schema::table('taxonomies', function (Blueprint $table) {
            $table->dropColumn('sort_order');
        });
    }
};
