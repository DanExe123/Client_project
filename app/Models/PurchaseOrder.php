<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseOrder extends Model
{
    use HasFactory;

    protected $fillable = [
        'supplier_id',
        'receipt_type',
        'order_date',
        'po_number',
        'total_amount',
        'remarks',
        'status',
    ];

    protected $casts = [
        'order_date' => 'date',
    ];

    /**
     * A purchase order belongs to a supplier.
     */
    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    /**
     * A purchase order has many items.
     */
    public function items()
    {
        return $this->hasMany(PurchaseOrderItem::class);
    }
}