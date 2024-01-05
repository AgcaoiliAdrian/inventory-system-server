<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Panel extends Model
{
    use HasFactory;

    protected $table = 'panel_stock';

    public $fillable = [
        'product_id',
        'grade_id',
        'barcode_id',
        'manufacturing_date',
        'quantity',
        'is_batch',
    ];

    public $timestamps = TRUE;
}