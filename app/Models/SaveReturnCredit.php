<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SaveReturnCredit extends Model
{
    use HasFactory; 

    protected $table = 'save_return_credit';

    protected $fillable = [
        'return_id',
        'customer_id',
        'order_date',
        'product_barcode',
        'product_description',
        'quantity',
        'unit_price',
        'subtotal',
    ];
    public function releasedItem()
{
    return $this->belongsTo(ReleasedItem::class, 'product_barcode', 'product_barcode')
        ->whereColumn('save_return_credit.customer_id', 'released_items.customer_id');
}

}
