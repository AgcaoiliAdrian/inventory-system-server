<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run()
    {
        $this->call([BrandSeeder::class]);
        $this->call([VariantSeeder::class]);
        $this->call([GradeSeeder::class]);
        $this->call([GlueTypeSeeder::class]);
        $this->call([ThicknessSeeder::class]);
        $this->call([ProductSeeder::class]);
    }
}
