<?php

namespace App\Services;

use League\OAuth2\Client\Provider\Google;
use PHPMailer\PHPMailer\OAuth;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;

class EmailService
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
        try {
            // Server settings
            $this->mail->isSMTP();
            $this->mail->Host = 'smtp.gmail.com';
            $this->mail->SMTPAuth = true;
            $this->mail->Username = $this->sys_email;
            $this->mail->Password = $this->token;
            $this->mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $this->mail->Port = 587;

            // OAuth2 authentication
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

            // Set sender
            $this->mail->setFrom($this->sys_email, $this->from_System);
        } catch (Exception $e) {
            throw new Exception('Error initializing PHPMailer: ' . $e->getMessage());
        }
    }

    public function getMailer()
    {
        return $this->mail;
    }
}
