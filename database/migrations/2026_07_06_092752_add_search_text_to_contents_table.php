<?php

use App\Models\Content;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('contents', 'search_text')) {
            Schema::table('contents', function (Blueprint $table) {
                $table->longText('search_text')->nullable()->after('grapesjs_html');
            });
        }

        Content::withTrashed()->with('fields')->chunkById(100, function ($contents) {
            foreach ($contents as $content) {
                $content->forceFill(['search_text' => $content->computeSearchText()])->saveQuietly();
            }
        });
    }

    public function down(): void
    {
        Schema::table('contents', function (Blueprint $table) {
            $table->dropColumn('search_text');
        });
    }
};
