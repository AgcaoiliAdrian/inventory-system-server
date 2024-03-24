<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use App\Models\GlueType;
use App\Models\Thickness;
use App\Models\Variant;
use App\Models\Grade;

class Brand extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'brands';
    protected $dates = ['deleted_at'];

    public $fillable = [
        'brand_name',
    ];
    public $timestamps = TRUE;

    public function glue(){
        return $this->hasMany(GlueType::class);
    }

    public function thickness(){
        return $this->hasMany(Thickness::class);
    }

    public function variant(){
        return $this->hasMany(Variant::class);
    }

    public function grade(){
        return $this->hasMany(Grade::class);
    }
}  
