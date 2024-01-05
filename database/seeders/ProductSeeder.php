<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

use App\Models\Product;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        Product::truncate();

        Product::create([
            'brand_id' => 1,
            'glue_type_id' => 1,
            'thickness_id' => 1,
            'variant_id' => NULL,
            'description' => '5.0 mm plywood',
            'price' => 900,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        Product::create([
            'brand_id' => 1,
            'glue_type_id' => 1,
            'thickness_id' => 2,
            'variant_id' => NULL,
            'description' => '9.0 mm resilient plywood',
            'price' => 800,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        //Start ZAMBOLY (ZAMBO-MARINE)

        Product::create([
            'brand_id' => 2,
            'glue_type_id' => 2,
            'thickness_id' => 6,
            'variant_id' => 1,
            'description' => '4.5 mm plywood',
            'price' => 550,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        Product::create([
            'brand_id' => 2,
            'glue_type_id' => 2,
            'thickness_id' => 5,
            'variant_id' => 1,
            'description' => '4.0 mm plywood',
            'price' => 500,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        //End ZAMBOLY (ZAMBO-MARINE)

        //Start ZAMBOLY (ZAMBO-ORIGINAL)

        Product::create([
            'brand_id' => 2,
            'glue_type_id' => 3,
            'thickness_id' => 6,
            'variant_id' => 2,
            'description' => '4.5 mm plywood',
            'price' => 500,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        
        Product::create([
            'brand_id' => 2,
            'glue_type_id' => 3,
            'thickness_id' => 7,
            'variant_id' => 2,
            'description' => '5.0 mm plywood',
            'price' => 600,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        //End ZAMBOLY (ZAMBO-ORIGINAL)

        //Start BUENAPLY

        Product::create([
            'brand_id' => 3,
            'glue_type_id' => 3,
            'thickness_id' => 12,
            'variant_id' => NULL,
            'description' => '5.0 mm plywood',
            'price' => 700,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        Product::create([
            'brand_id' => 3,
            'glue_type_id' => 3,
            'thickness_id' => 13,
            'variant_id' => NULL,
            'description' => '10.0 mm plywood',
            'price' => 750,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        //End BUENAPLY
    }
}
