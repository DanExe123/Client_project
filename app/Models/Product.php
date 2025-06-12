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
        'lowest_uom_quantity',
        'price',
        'status',
        'selling_price',
    ];
}