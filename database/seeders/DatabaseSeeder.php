<?php

/**
 * Laravix CMS — Copyright (C) 2026 Martin Koudela (laravix.com)
 * Licensed under GPL-3.0-or-later. See LICENSE for details.
 */

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Laravix\Cms\Database\Seeders\DatabaseSeeder as CmsDatabaseSeeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call(CmsDatabaseSeeder::class);
    }
}
