<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

use App\Models\Brand;

class BrandSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        Brand::truncate();

        Brand::create([
            'brand_name' => "SANTA ROSA",
            'created_at' => now(),
            'updated_at' => now()
        ]);

        Brand::create([
            'brand_name' => "ZAMBOPLY",
            'created_at' => now(),
            'updated_at' => now()
        ]);

        Brand::create([
            'brand_name' => "BUENAPLY",
            'created_at' => now(),
            'updated_at' => now()
        ]);
    }
}
