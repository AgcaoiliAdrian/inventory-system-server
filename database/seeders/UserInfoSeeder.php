<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\UserInformation;
use Illuminate\Support\Facades\DB;

class UserInfoSeeder extends Seeder
{

    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        UserInformation::truncate();

        UserInformation::create([
            "employee_name" => 'Adrian Agcaoili',
            "job_position" => 'CP II',
            "employment_status" => 'Regular',
            "system_role" => 'Admin',
            "branch_assigned" => 'Zamboanga',
            "contact_number" => '09605075322',
            'created_at' => now(),
            'updated_at' => now()
        ]);

        UserInformation::create([
            "employee_name" => 'Head Office',
            "job_position" => 'Head',
            "employment_status" => 'Regular',
            "system_role" => 'Admin',
            "branch_assigned" => 'Head Office',
            "contact_number" => '09605075322',
            'created_at' => now(),
            'updated_at' => now()
        ]);

        UserInformation::create([
            "employee_name" => 'Grader A',
            "job_position" => 'Staff',
            "employment_status" => 'Regular',
            "system_role" => 'Grader',
            "branch_assigned" => 'Zamboanga',
            "contact_number" => '09605075322',
            'created_at' => now(),
            'updated_at' => now()
        ]);
    }
}
