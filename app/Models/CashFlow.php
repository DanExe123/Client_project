<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CashFlow extends Model
{
    protected $fillable = [
        'date',
        'beginning_balance',
        'customer_payments',
        'payment_to_supplier',
        'expenses',
        'ending_balance',
    ];

    protected $casts = [
        'date' => 'date',
        'beginning_balance' => 'decimal:2',
        'customer_payments' => 'decimal:2',
        'payment_to_supplier' => 'decimal:2',
        'expenses' => 'decimal:2',
        'ending_balance' => 'decimal:2',
    ];
}