<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

use App\Models\Thickness;

class ThicknessSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        Thickness::truncate();

        Thickness::create([
            // 'brand_id' => 1,
            'value' => '5.0',
            'unit' => 'mm',
            'created_at' => now(),
            'updated_at' => now()
        ]);

        Thickness::create([
            // 'brand_id' => 1,
            'value' => '9.0',
            'unit' => 'mm',
            'created_at' => now(),
            'updated_at' => now()
        ]);

        Thickness::create([
            // 'brand_id' => 1,
            'value' => '11.0',
            'unit' => 'mm',
            'created_at' => now(),
            'updated_at' => now()
        ]);

        Thickness::create([
            // 'brand_id' => 1,
            'value' => '18.0',
            'unit' => 'mm',
            'created_at' => now(),
            'updated_at' => now()
        ]);

        Thickness::create([
            // 'brand_id' => 2,
            'value' => '4.0',
            'unit' => 'mm',
            'created_at' => now(),
            'updated_at' => now()
        ]);

        Thickness::create([
            // 'brand_id' => 2,
            'value' => '4.5',
            'unit' => 'mm',
            'created_at' => now(),
            'updated_at' => now()
        ]);

        Thickness::create([
            // 'brand_id' => 2,
            'value' => '5.0',
            'unit' => 'mm',
            'created_at' => now(),
            'updated_at' => now()
        ]);

        Thickness::create([
            // 'brand_id' => 2,
            'value' => '9.0',
            'unit' => 'mm',
            'created_at' => now(),
            'updated_at' => now()
        ]);

        Thickness::create([
            // 'brand_id' => 2,
            'value' => '10.0',
            'unit' => 'mm',
            'created_at' => now(),
            'updated_at' => now()
        ]);
        
        Thickness::create([
            // 'brand_id' => 2,
            'value' => '11.0',
            'unit' => 'mm',
            'created_at' => now(),
            'updated_at' => now()
        ]);

        Thickness::create([
            // 'brand_id' => 2,
            'value' => '18.0',
            'unit' => 'mm',
            'created_at' => now(),
            'updated_at' => now()
        ]);

        Thickness::create([
            // 'brand_id' => 3,
            'value' => '5.0',
            'unit' => 'mm',
            'created_at' => now(),
            'updated_at' => now()
        ]);

        Thickness::create([
            // 'brand_id' => 3,
            'value' => '10.0',
            'unit' => 'mm',
            'created_at' => now(),
            'updated_at' => now()
        ]);

        Thickness::create([
            // 'brand_id' => 3,
            'value' => '18.0',
            'unit' => 'mm',
            'created_at' => now(),
            'updated_at' => now()
        ]);
    }
}
