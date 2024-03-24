<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Brand;

class Thickness extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'thickness';
    protected $dates = ['deleted_at'];

    public $fillable = [
        // 'brand_id',
        'value',
        'unit'
    ];

    public $timestamps = TRUE;
}
