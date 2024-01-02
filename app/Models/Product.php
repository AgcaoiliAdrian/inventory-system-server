<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\Brand;
use App\Models\GlueType;
use App\Models\Thickness;
use App\Models\Variant;

class Product extends Model
{
    use HasFactory;

    protected $table = 'product';

    public $fillable = [
        'brand_id',
        'glue_type_id',
        'thickness_id',
        'variant_id',
        'manufacturing_date',
        'description',
        'price'
    ];

    public $timestamps = TRUE;

    public function brand(){
        return $this->belongsTo(Brand::class, 'brand_id');
    }

    public function glue(){
        return $this->belongsTo(GlueType::class, 'glue_type_id');
    }

    public function thickness(){
        return $this->belongsTo(Thickness::class, 'thickness_id');
    }

    public function variant(){
        return $this->belongsTo(Variant::class, 'variant_id');
    }
}
