<?php
 
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ReleasedItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'sales_release_id',
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
        'printed_at',
        'add_vat',
        'product_id',
        'product_description',
        'product_barcode',
        'quantity',
        'unit_price',
        'discount_item',
        'subtotal',
    ];

    // Relationships
    public function salesRelease()
    {
        return $this->belongsTo(SalesRelease::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
    public function returnCredits()
{
    return $this->hasMany(SaveReturnCredit::class, 'product_barcode', 'product_barcode')
        ->whereColumn('save_return_credit.customer_id', 'released_items.customer_id');
}
    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }
}
