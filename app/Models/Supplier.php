<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    use HasFactory;

    protected $table = 'supplier';

    public $fillable = [
        'supplier_name',
        'category',
        'contact_number',
        'contact_person'
    ];

    public $timestamps = TRUE;

    public function plywoodSupplied()
    {
        return $this->hasMany(PlywoodSupplied::class, 'supplier_id')->select('supplier_id', 'plywood_type', 'plywood_brand');
    } 

    public function glueSupplied()
    {
        return $this->hasMany(GlueSupplied::class, 'supplier_id')->select('supplier_id', 'glue_type', 'glue_brand');
    } 
}
