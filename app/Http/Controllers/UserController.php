<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Hash;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\UserInformation;

class UserController extends Controller
{
    public function register(Request $request){
        try {
            $user_info = new UserInformation();
            $user_info->employee_name = $request->employee_name;
            $user_info->job_position = $request->job_position;
            $user_info->employment_status = $request->employment_status;
            $user_info->system_role = $request->system_role;
            $user_info->branch_assigned = $request->branch_assigned;
            $user_info->contact_number = $request->contact_number;
            $user_info->save();

            if($user_info){
                $account = new User();
                $account->email = $request->email;
                $temp_password = 'Mega_Plywood2024@';
                $account->password = Hash::make($temp_password);
                $account -> save();
            }
    
            if($user_info){
                return response()->json(['message' => 'User registered successfully.']);
            }
            
        } catch (\Throwable $th) {
            return response()->json(['error' => $th->getMessage()], 500);
        }
    }
}