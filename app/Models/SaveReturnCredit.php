<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SaveReturnCredit extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'save_return_credits';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'return_id',
        'customer_id',
        'product_barcode',
        'product_description',
        'quantity',
        'unit_price',
        'subtotal',
        'released_item_id',
        'applied_amount',
    ];

    /**
     * Get the customer return that owns the SaveReturnCredit.
     */
    public function return()
    {
        return $this->belongsTo(CustomerReturn::class, 'return_id');
    }

    /**
     * Get the customer associated with the SaveReturnCredit.
     */
    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }

    /**
     * Get the released item that the credit was applied to.
     */
    public function releasedItem()
{
    return $this->belongsTo(ReleasedItem::class, 'product_barcode', 'product_barcode')
        ->whereColumn('save_return_credit.customer_id', 'released_items.customer_id');
}
public function supplier()
{
    return $this->belongsTo(Supplier::class, 'supplier_id');
}


}