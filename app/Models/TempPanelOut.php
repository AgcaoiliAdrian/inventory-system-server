<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TempPanelOut extends Model
{
    use HasFactory;

    protected $table = 'temp_panel_out';

    public $fillable = [
        'panel_stock_id',
        'crate_stock_id'
    ];

    public $timestamps = TRUE;
}
