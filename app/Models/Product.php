<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'barcode',
        'supplier',
        'description',
        'quantity',
        'highest_uom',
        'lowest_uom',
        'price',
        'status',
        'selling_price',
    ];
}