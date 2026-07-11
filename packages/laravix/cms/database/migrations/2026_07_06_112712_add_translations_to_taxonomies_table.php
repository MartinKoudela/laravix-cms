<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('taxonomies', 'translations')) {
            Schema::table('taxonomies', function (Blueprint $table) {
                $table->json('translations')->nullable()->after('slug');
            });
        }
    }

    public function down(): void
    {
        Schema::table('taxonomies', function (Blueprint $table) {
            $table->dropColumn('translations');
        });
    }
};
