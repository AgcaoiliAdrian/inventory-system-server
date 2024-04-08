<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Crate extends Model
{
    use HasFactory;

    protected $table = 'crate_stock';

    public $fillable = [
        'barcode_id',
        'manufacturing_date',
        'grader',
        'quantity',
        'batch_number'
    ];

    public function barcodeDetails()
    {
        return $this->belongsTo(BarcodeDetails::class, 'barcode_id')->select('id', 'brand_id', 'variant_id', 'thickness_id', 'barcode_number');
    }

    public function grade()
    {
        return $this->belongsTo(Grade::class, 'grade_id')->select('id', 'grading');
    }

    public $timestamps = TRUE;
    
}
