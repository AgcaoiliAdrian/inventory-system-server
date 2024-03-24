<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Brand;

class GlueType extends Model
{
    use HasFactory;

    protected $table = 'glue_type';

    public $fillable = [
        // 'brand_id',
        'type',
        'brand'
    ];
    public $timestamps = TRUE;

    public function brand()
    {
        return $this->belongsTo(Brand::class, 'brand_id');
    }

}
