<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

use App\Models\GlueType;

class GlueTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        GlueType::truncate();

        GlueType::create([
            // 'brand_id' => 1,
            'type' => 'I',
            'brand' => 'Phenores',
            'created_at' => now(),
            'updated_at' => now()
        ]);

        GlueType::create([
            // 'brand_id' => 2,
            'type' => 'I',
            'brand' => 'Bondtite 242',
            'created_at' => now(),
            'updated_at' => now()
        ]);

        GlueType::create([
            // 'brand_id' => 2,
            'type' => 'II',
            'brand' => 'Ures LOFO',
            'created_at' => now(),
            'updated_at' => now()
        ]);

        GlueType::create([
            // 'brand_id' => 3,
            'type' => 'I',
            'brand' => 'Bondite 242',
            'created_at' => now(),
            'updated_at' => now()
        ]);
        
    }
}
