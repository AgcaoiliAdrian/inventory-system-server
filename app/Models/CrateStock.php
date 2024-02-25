<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CrateStock extends Model
{
    use HasFactory;
    
    protected $table = 'crate_stock';

    public $fillable = [
        'panel_stock_id',
        'batch_number'
    ];
    
    public $timestamps = TRUE;
}
