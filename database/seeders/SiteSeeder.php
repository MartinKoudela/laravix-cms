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
        // defailt localhost domain for docker or other server
        Site::factory()->create([
            'name' => 'localhost',
            'domain' => 'localhost'
        ]);
        Site::factory()->count(6)->create();
    }
}
