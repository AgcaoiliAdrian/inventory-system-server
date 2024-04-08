<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{

    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        User::truncate();

        User::create([
            "user_info_id" => 1,
            "email" => 'supervisor@gmail.com',
            "password" => Hash::make('Mega_Plywood2024@'),
            'created_at' => now(),
            'updated_at' => now()
        ]);

        User::create([
            "user_info_id" => 2,
            "email" => 'headoffice@gmail.com',
            "password" => Hash::make('Mega_Plywood2024@'),
            'created_at' => now(),
            'updated_at' => now()
        ]);

        User::create([
            "user_info_id" => 3,
            "email" => 'grader@gmail.com',
            "password" => Hash::make('Mega_Plywood2024@'),
            'created_at' => now(),
            'updated_at' => now()
        ]);
    }
}
