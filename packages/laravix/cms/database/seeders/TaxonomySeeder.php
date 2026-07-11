<?php

namespace Laravix\Cms\Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Laravix\Cms\Models\Taxonomy;

class TaxonomySeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        Taxonomy::factory()->count(20)->create();
    }
}
