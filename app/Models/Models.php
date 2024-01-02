<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\Brand;
use App\Models\Variant;

class Models extends Model
{
    use HasFactory;

    protected $table = 'model';

    public $fillable = [
        'brand_id',
        'variant_id'
    ];
    public $timestamps = TRUE;

    public function brand(){
        return $this->belongsTo(Brand::class);
    }

    public function variant(){
        return $this->belongsTo(Variant::class);
    }
}
