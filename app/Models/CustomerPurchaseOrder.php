<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerPurchaseOrder extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'receipt_type',
        'order_date',
        'po_number',
        'total_amount',
        'remarks',
        'purchase_discount',
        'status',
    ];

    protected $casts = [
        'order_date' => 'date',
    ];

    /**
     * A purchase order belongs to a customer.
     */
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    /**
     * A purchase order has many items.
     */
    public function items()
    {
        return $this->hasMany(CustomerPurchaseOrderItem::class);
    }
}