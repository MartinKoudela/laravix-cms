<?php

namespace Database\Seeders;

use App\Models\Taxonomy;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TaxonomySeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        Taxonomy::factory()->count(20)->create();
    }
}
