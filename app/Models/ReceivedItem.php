<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ReceivedItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'receiving_id',
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
        'barcode',
        'description',
        'quantity',
        'unit_price',
        'discount',
        'subtotal',
    ];

    // Relationships
    public function receiving()
    {
        return $this->belongsTo(Receiving::class);
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }
}
