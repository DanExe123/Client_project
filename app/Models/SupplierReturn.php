<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SupplierReturn extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'order_date',
        'total_amount',
        'remarks',
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
        return $this->belongsTo(Supplier::class);
    }

    /**
     * A purchase order has many items.
     */
    public function items()
    {
        return $this->hasMany(SupplierReturnItem::class, 'return_id');
    }
}