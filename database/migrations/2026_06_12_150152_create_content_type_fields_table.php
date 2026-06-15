<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('content_type_fields', function (Blueprint $table) {
            $table->id();
            $table->foreignId('site_id')->constrained()->cascadeOnDelete();
            $table->string('content_type');
            $table->string('key');
            $table->string('label');
            $table->string('type')->default('text');
            $table->string('group')->nullable();
            $table->string('hint')->nullable();
            $table->json('config')->nullable();
            $table->boolean('required')->default(false);
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->timestamps();

            $table->unique(['site_id', 'content_type', 'key']);
            $table->index(['site_id', 'content_type']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('content_type_fields');
    }
};
