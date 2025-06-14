<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Recievings extends Model
{
    use HasFactory;

    protected $fillable = [
        'purchase_order_item_id',
        'status',
    ];

    /**
     * Relationship: A Receiving belongs to a PurchaseOrderItem
     */
    public function purchaseOrderItem()
    {
        return $this->belongsTo(PurchaseOrderItem::class);
    }
}
