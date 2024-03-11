<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\BarcodeDetails;

class TempBatchIn extends Model
{
    use HasFactory;

    protected $table = 'temp_batch_in';

    public $fillable = [
        'barcode_id',
        'grade_id',
        'manufacturing_date',
        'quantity',
        'status'
    ];

    public $timestamps = TRUE;
}
