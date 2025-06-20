<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Supplier extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'address',
        'term',
        'tin_number',
        'contact',
        'contact_person',
        'status',
    ];
    public function receiveditem()
    {
        return $this->hasMany(\App\Models\ReceivedItem::class);
    }
    public function receivings()
    {
        return $this->hasMany(Receiving::class);
    }
}

