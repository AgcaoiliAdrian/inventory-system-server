<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Panel;
use App\Models\BarcodeDetails;
use App\Models\Grade;
use App\Models\Thickness;

class StockOut extends Model
{
    use HasFactory;

    protected $table = 'stock_out';

    public $fillable = [
        'panel_stock_id',
        'is_batch',
        'stock_out_date'
    ];

    public function panel()
    {
        return $this->belongsTo(Panel::class, 'panel_stock_id')->select('id', 'barcode_id', 'grade_id', 'quantity', 'is_batch');
    }

    public function barcodeDetails()
    {
        return $this->hasOneThrough(BarcodeDetails::class, Panel::class, 'barcode_id', 'id', 'panel_stock_id', 'barcode_id');
    }

    public $timestamps = TRUE;
}
