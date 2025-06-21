<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Receiving extends Model
{
    use HasFactory;

    protected $fillable = [
        'po_number',
        'supplier_id',
        'receipt_type',
        'order_date',
        'purchase_discount',
        'grand_total',
        'remarks',
        'status',
        'approved_by',
        'approved_at',
    ];

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function items()
    {
        return $this->hasMany(ReceivingItem::class);
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }
    public function receivingItems()
    {
        return $this->hasMany(ReceivingItem::class, 'receiving_id');
    }
    public function receivedItems()
    {
        return $this->hasMany(ReceivedItem::class);
    }


}
