<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CustomerTable extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'email',
        'address',
        'contact',
        'contact_person',
        'term',
        'status'

    ];
}
