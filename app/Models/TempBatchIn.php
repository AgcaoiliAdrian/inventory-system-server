<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\BarcodeDetails;
use App\Models\Brand;


class TempBatchIn extends Model
{
    use HasFactory;

    protected $table = 'temp_batch_in';

    public $fillable = [
        'barcode_id',
        'grade_id',
        'brand_id',
        'variant_id',
        'grader',
        'manufacturing_date',
        'quantity',
        'status'
    ];

    public $timestamps = TRUE;

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

    public function barcode()
    {
        return $this->belongsTo(BarcodeDetails::class)->select('id', 'barcode_number', 'thickness_id', 'glue_type_id');
    }
}
