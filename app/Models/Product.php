<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'barcode',
        'supplier_id',
        'description',
        'quantity',
        'highest_uom',
        'quantity_lowest',
        'lowest_uom',
        'lowest_uom_quantity',
        'price',
        'status',
        'selling_price',
        'damages',
    ];

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

}