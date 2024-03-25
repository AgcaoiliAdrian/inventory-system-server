<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\BarcodeDetails;

class Panel extends Model
{
    use HasFactory;

    protected $table = 'panel_stock';

    public $fillable = [
        'barcode_id',
        // 'grade_id',
        'manufacturing_date',
        'quantity',
        'price',
        'status'
    ];

    public $timestamps = TRUE;

    public function barcodeDetails()
    {
        return $this->belongsTo(BarcodeDetails::class, 'barcode_id')->select('id', 'brand_id', 'variant_id', 'thickness_id', 'barcode_number');
    }

    public function grade()
    {
        return $this->belongsTo(Grade::class, 'grade_id')->select('id', 'grading');
    }

    public function barcode()
    {
        return $this->belongsTo(BarcodeDetails::class, 'barcode_id')->select('id', 'brand_id', 'variant_id', 'thickness_id', 'barcode_number');
    }
}
