<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockOut extends Model
{
    use HasFactory;

    protected $table = 'stock_out';

    public $fillable = [
        'panel_stock_id',
        'crate_stock_id',
        'stock_out_date'
    ];

    public $timestamps = TRUE;
}
