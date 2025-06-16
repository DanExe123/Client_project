<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ReceivingItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'receiving_id',
        'barcode',
        'description',
        'quantity',
        'unit_price',
        'discount',
        'subtotal',
    ];

    public function receiving()
    {
        return $this->belongsTo(Receiving::class);
    }
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}