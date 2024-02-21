<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Grade extends Model
{
    use HasFactory;

    protected $table = 'grade';

    public $fillable = [
        // 'brand_id',
        'grading',
    ];
    public $timestamps = TRUE;
}
