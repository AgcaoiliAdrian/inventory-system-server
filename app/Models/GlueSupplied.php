<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GlueSupplied extends Model
{
    use HasFactory;

    protected $table = 'glue_supplied';

    public $fillable = [
        'supplier_id',
        'glue_type',
        'glue_brand'
    ];
    public $timestamps = TRUE;
}
