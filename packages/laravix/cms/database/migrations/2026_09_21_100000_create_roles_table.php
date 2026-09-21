<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Laravix\Cms\Enums\SiteRole;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('roles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('site_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->string('slug');
            $table->json('permissions');
            $table->boolean('is_system')->default(false);
            $table->timestamps();

            $table->unique(['site_id', 'slug']);
        });

        $now = now();

        DB::table('sites')->select('id')->orderBy('id')->each(function (object $site) use ($now): void {
            foreach (SiteRole::cases() as $role) {
                DB::table('roles')->insert([
                    'site_id' => $site->id,
                    'name' => ucfirst($role->value),
                    'slug' => $role->value,
                    'permissions' => json_encode($role->defaultPermissions()),
                    'is_system' => true,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('roles');
    }
};
