<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Model\Brand;

class Variant extends Model
{
    use HasFactory;

    protected $table = 'variant';

    public $fillable = [
        'brand_id',
        'variant_name'
    ];

    public $timestamps = TRUE;
}
