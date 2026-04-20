<?php

namespace Database\Seeders;

use App\Models\Media;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MediaSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        Media::factory()->count(15)->create();

    }
}
