<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalesReleaseItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'sales_release_id',
        'product_id',
        'product_description',
        'product_barcode',
        'quantity',
        'unit_price',
        'discount',
        'subtotal',
    ];

    public function release()
    {
        return $this->belongsTo(SalesRelease::class, 'sales_release_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
