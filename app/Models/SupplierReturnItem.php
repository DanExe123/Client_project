<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SupplierReturnItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'return_id',
        'product_id',
        'product_description', // Stored for historical data
        'product_barcode',     // Stored for historical data
        'quantity',
        'unit_price',   
        'subtotal',
    ];

    /**
     * A purchase order item belongs to a purchase order.
     */
    public function purchaseOrder()
    {
        return $this->belongsTo(SupplierReturn::class);
    }

    /**
     * A purchase order item belongs to a product.
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}