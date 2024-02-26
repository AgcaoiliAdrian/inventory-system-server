<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Panel;

class BarcodeDetails extends Model
{
    use HasFactory;

    protected $table = 'barcode_details';

    public $fillable = [
        'brand_id',
        'variant_id',
        'glue_type_id',
        'thickness_id',
        'barcode_number'
    ];
    public $timestamps = TRUE;

    public function panels()
    {
        return $this->hasMany(Panel::class, 'barcode_id');
    }

}
