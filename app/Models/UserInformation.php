<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserInformation extends Model
{
    use HasFactory;

    protected $table = 'user_information';

    public $fillable = [
        'employee_name',
        'job_position ',
        'employment_status ',
        'system_role ',
        'branch_assigned ',
        'contact_number '
    ];

    public $timestamps = TRUE;
}
