<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentInvoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'sales_release_id',
        'invoice_number',
        'invoice_date',
        'invoice_amount',
        'amount',
        'deduction',
        'remarks',
        'payment_method',
        'bank',
        'cheque_number',
        'check_date',
        'reference_number',
        'transaction_date',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function salesRelease()
    {
        return $this->belongsTo(SalesRelease::class);
    }
}
