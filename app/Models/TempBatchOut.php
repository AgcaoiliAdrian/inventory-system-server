<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TempBatchOut extends Model
{
    use HasFactory;

    protected $table = 'temp_batch_out';

    public $fillable = [
        'batch_number',
    ];

    public $timestamps = TRUE;

    public function brand()
    {
        return $this->belongsTo(Brand::class)->select('id', 'brand_name');
    }

    public function variant()
    {
        return $this->belongsTo(Variant::class)->select('id', 'variant_name');
    }

    public function thickness()
    {
        return $this->belongsTo(Thickness::class)->select('id', 'value', 'unit');
    }

    public function grade()
    {
        return $this->belongsTo(Grade::class)->select('id', 'grading');
    }

    public function barcode()
    {
        return $this->belongsTo(BarcodeDetails::class)->select('id', 'barcode_number');
    }
}