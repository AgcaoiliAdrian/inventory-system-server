<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Brand;

class Thickness extends Model
{
    use HasFactory;

    protected $table = 'thickness';

    public $fillable = [
        'brand_id',
        'value',
        'unit'
    ];

    public $timestamps = TRUE;
}
