<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CustomerReturn extends Model
{
    use HasFactory;

    protected $fillable = [
        'return_type',
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
        return $this->belongsTo(Customer::class);
    }

    /**
     * A purchase order has many items.
     */
    public function items()
    {
        return $this->hasMany(CustomerReturnItem::class, 'return_id');
    }

    public function return()
    {
        return $this->belongsTo(CustomerReturn::class, 'return_id');
    }

}