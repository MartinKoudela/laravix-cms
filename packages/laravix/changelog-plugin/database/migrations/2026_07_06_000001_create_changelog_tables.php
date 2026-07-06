<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('changelog_releases', function (Blueprint $table) {
            $table->id();
            $table->foreignId('site_id')->constrained()->cascadeOnDelete();
            $table->string('version');
            $table->string('title')->nullable();
            $table->date('released_at');
            $table->timestamps();

            $table->unique(['site_id', 'version']);
        });

        Schema::create('changelog_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('changelog_release_id')->constrained()->cascadeOnDelete();
            $table->string('type', 20)->default('added');
            $table->text('text');
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('changelog_items');
        Schema::dropIfExists('changelog_releases');
    }
};
