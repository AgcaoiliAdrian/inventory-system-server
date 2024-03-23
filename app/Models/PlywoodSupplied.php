<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PlywoodSupplied extends Model
{
    use HasFactory;

    protected $table = 'plywood_supplied';

    public $fillable = [
        'supplied_id',
        'plywood_type',
        'plywood_brand',
    ];
    public $timestamps = TRUE;
}
