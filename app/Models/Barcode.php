<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Barcode extends Model
{
    use HasFactory;

    protected $table = 'barcode';

    public $fillable = [
        'barcode_details_id',
        'barcode_number'
    ];
    public $timestamps = TRUE;
}
