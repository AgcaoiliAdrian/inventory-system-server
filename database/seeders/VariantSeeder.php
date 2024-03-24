<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

use App\Models\Variant;

class VariantSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        Variant::truncate();

        Variant::create([
            'brand_id' => 2,
            'variant_name' => "GENUINE MARINE",
            'created_at' => now(),
            'updated_at' => now()
        ]);

        Variant::create([
            'brand_id' => 2,
            'variant_name' => "ORDINARY",
            'created_at' => now(),
            'updated_at' => now()
        ]);
    }
}
