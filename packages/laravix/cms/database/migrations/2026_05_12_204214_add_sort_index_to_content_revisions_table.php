<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('content_revisions', function (Blueprint $table) {
            $table->index(['content_id', 'created_at', 'id'], 'content_revisions_content_id_created_at_id_index');
        });
    }

    public function down(): void
    {
        Schema::table('content_revisions', function (Blueprint $table) {
            $table->dropIndex('content_revisions_content_id_created_at_id_index');
        });
    }
};
