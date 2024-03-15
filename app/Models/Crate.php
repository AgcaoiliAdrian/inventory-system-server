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
        'grade_id',
        'manufacturing_date',
        'quantity',
        'price',
        'batch_number'
    ];

    public $timestamps = TRUE;
    
}
