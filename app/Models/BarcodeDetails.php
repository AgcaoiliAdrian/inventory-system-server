<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Panel;
use App\Models\Grade;


class BarcodeDetails extends Model
{
    use HasFactory;

    protected $table = 'barcode_details';

    public $fillable = [
        'brand_id',
        'variant_id',
        'glue_type_id',
        'thickness_id',
        'barcode_number'
    ];

    public $timestamps = TRUE;

    public function panels()
    {
        return $this->hasMany(Panel::class, 'barcode_id');
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class, 'brand_id')->select('id', 'brand_name');
    }
    
    public function variant()
    {
        return $this->belongsTo(Variant::class, 'variant_id')->select('id', 'variant_name');
    }

    public function thickness()
    {
        return $this->belongsTo(Thickness::class, 'thickness_id')->select('id', 'value', 'unit');
    }

    public function glue()
    {
        return $this->belongsTo(GlueType::class, 'glue_type_id')->select('id', 'type', 'brand');
    }

    public function grade()
    {
        return $this->belongsTo(Grade::class)->select('id', 'grading');
    }

    public function crateStock()
    {
        return $this->hasMany(Crate::class, 'barcode_id');
    }
    
    public function panelStock()
    {
        return $this->hasMany(Panel::class, 'barcode_id');
    }    
    
}
