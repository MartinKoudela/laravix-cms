<?php

namespace Database\Seeders;

use App\Models\Site;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SiteSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        Site::factory()->count(15)->create();
    }
}
