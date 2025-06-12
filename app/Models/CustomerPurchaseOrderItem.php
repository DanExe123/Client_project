<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerPurchaseOrderItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'purchase_order_id',
        'product_id',
        'product_description', // Stored for historical data
        'product_barcode',     // Stored for historical data
        'quantity',
        'unit_price',
        'product_discount',
        'subtotal',
    ];

    /**
     * A purchase order item belongs to a purchase order.
     */
    public function purchaseOrder()
    {
        return $this->belongsTo(CustomerPurchaseOrder::class);
    }

    /**
     * A purchase order item belongs to a product.
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}