<?php

namespace Database\Seeders;

use App\Models\Content;
use App\Models\ContentField;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ContentSeeder extends Seeder
{
    use WithoutModelEvents;
    public function run(): void
    {
        Content::factory()->count(20)->create();
        ContentField::factory()->count(100)->create();
    }
}
