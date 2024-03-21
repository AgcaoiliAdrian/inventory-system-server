<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Hash;
use League\OAuth2\Client\Provider\Google;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\OAuth;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use Illuminate\Support\Facades\Auth;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\UserInformation;

class UserController extends Controller
{
    private $client_id;
    private $client_secret;
    private $provider;
    private $token;
    private $sys_email;
    private $from_System;
    private $mail;

    public function __construct()
    {
        $this->client_id = env('GOOGLE_API_CLIENT_ID');
        $this->client_secret = env('GOOGLE_API_CLIENT_SECRET');
        $this->token = env('SYSTEM_EMAIL_TOKEN');
        $this->sys_email = env('SYSTEM_EMAIL');
        $this->from_System = env('SYSTEM_NAME');
        $this->provider = new Google([
            'clientId' => $this->client_id,
            'clientSecret' => $this->client_secret,
        ]);

        // Initialize PHPMailer
        $this->mail = new PHPMailer(true);
        $this->mail->isSMTP();
        $this->mail->Host = 'smtp.gmail.com'; // Your SMTP server
        $this->mail->SMTPAuth = true;
        $this->mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $this->mail->Port = 587; // SMTP port (can be 587 or 465)
        $this->mail->AuthType = 'XOAUTH2';
            $this->mail->setOAuth(
                new OAuth([
                    'provider' => $this->provider,
                    'clientId' => $this->client_id,
                    'clientSecret' => $this->client_secret,
                    'refreshToken' => $this->token,
                    'userName' => $this->sys_email,
                ])
            );
    }
    
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


            if (!$user_info) {
                return response()->json(['message' => 'Failed to register user.'], 500);
            }
            
            if($user_info){
                $account = new User();
                $account -> user_info_id = $user_info -> id;
                $account->email = $request->email;
                $temp_password = 'Mega_Plywood2024@';
                $account->password = Hash::make($temp_password);
                $account->save();

                if (!$account) {
                    return response()->json(['message' => 'Email has been already used'], 500);
                }
    
                // Send email with temporary passwordn 
                try {
                    //Recipients
                    $this->mail->setFrom($this->sys_email, $this->from_System);
                    $this->mail->addAddress($request->email); // Add a recipient
    
                    // Content
                    $this->mail->isHTML(true); // Set email format to HTML
                    $this->mail->CharSet = PHPMailer::CHARSET_UTF8;
                    $this->mail->Subject = 'Temporary Password';
                    $this->mail->Body = 'Your temporary password is: ' . $temp_password;
    
                    $this->mail->send();
                    return response()->json(['message' => 'User registered successfully. Email sent with temporary password.'], 200);
                } catch (Exception $e) {
                    return response()->json(['message' => 'User registered successfully. Failed to send email.'], 401);
                }
            }   
               
        } catch (\Throwable $th) {
            return response()->json(['error' => $th->getMessage(), 'message' => "Failed to register user"], 500);
        }
    }

    public function login(Request $request)
    {
        try {
            if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
                $user = Auth::user()->load('info');

                // Check if the password used is the default password
                if ($user->password === 'Mega_Plywood2024@') {
                    // Update the password
                    $this->updatePassword($user, $request->new_password);

                    return response()->json(['message' => 'You are using the default password. Password updated successfully.'], 200);
                }

                // Authentication successful
                return response()->json($user, 200);
            }

            // Authentication failed
            return response()->json(['error' => 'Invalid credentials'], 401);
        } catch (\Throwable $th) {
            return response()->json(['error' => $th->getMessage()], 500);
        }
    }

    private function updatePassword($user, $newPassword)
    {
        // Update user's password
        $user->password = Hash::make($newPassword);
        $user->save();
    }
}