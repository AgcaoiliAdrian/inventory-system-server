<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

use App\Models\Grade;

class GradeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        Grade::truncate();

        Grade::create([
            'brand_id' => 1,
            'grading' => 'SR',
            'created_at' => now(),
            'updated_at' => now()
        ]);

        Grade::create([
            'brand_id' => 1,
            'grading' => 'EE',
            'created_at' => now(),
            'updated_at' => now()
        ]);

        Grade::create([
            'brand_id' => 2,
            'grading' => 'Local C',
            'created_at' => now(),
            'updated_at' => now()
        ]);

        Grade::create([
            'brand_id' => 2,
            'grading' => 'Local D',
            'created_at' => now(),
            'updated_at' => now()
        ]);

        Grade::create([
            'brand_id' => 2,
            'grading' => 'PZS',
            'created_at' => now(),
            'updated_at' => now()
        ]);

        Grade::create([
            'brand_id' => 2,
            'grading' => 'PTR',
            'created_at' => now(),
            'updated_at' => now()
        ]);

        Grade::create([
            'brand_id' => 3,
            'grading' => 'Local C',
            'created_at' => now(),
            'updated_at' => now()
        ]);

        Grade::create([
            'brand_id' => 3,
            'grading' => 'Local D',
            'created_at' => now(),
            'updated_at' => now()
        ]);
    }
}
