<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Brand;

class GlueType extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'glue_type';
    protected $dates = ['deleted_at'];

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
