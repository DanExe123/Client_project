<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExpenseTable extends Model
{
    use HasFactory;

    // Specify the table name if it doesn't follow Laravel's naming convention
    protected $table = 'expenses_table';

    // Define the fillable attributes
    protected $fillable = [
        'date',
        'category',
        'payee',
        'description',
        'amount',
        'paid_by',
        'remarks',
    ];
}
