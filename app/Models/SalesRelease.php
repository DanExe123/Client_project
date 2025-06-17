<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalesRelease extends Model
{
    use HasFactory;

    protected $fillable = [
        'purchase_order_id',
        'receipt_type',
        'customer_id',
        'release_date',
        'discount',
        'remarks',
        'created_by',
        'vat_percent',
        'total_amount',
        'amount_net_of_vat',
        'total_with_vat',
        'add_vat',
    ];
    public function items()
    {
        return $this->hasMany(SalesReleaseItem::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function purchaseOrder()
    {
        return $this->belongsTo(CustomerPurchaseOrder::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function releasedItems()
    {
        return $this->hasMany(ReleasedItem::class);
    }

}
