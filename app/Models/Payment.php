<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'date',
        'supplier_id',
        'payment_method',
        'bank',
        'cheque_number',
        'check_date',
        'reference_number',
        'transaction_date',
        'total_amount',
        'amount_paid',
        'ewt_amount',
        'deduction',
        'remarks',
        'received_item_ids',
    ];

    protected $casts = [
        'received_item_ids' => 'array',
        'check_date' => 'date',
        'transaction_date' => 'date',
        'date' => 'date',
    ];

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function returns()
    {
        return $this->belongsToMany(SupplierReturn::class, 'payment_supplier_return');
    }
}