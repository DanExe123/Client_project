<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class Customer extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'email',   
        'address',
        'contact',
        'contact_person',
        'term',
        'cust_tin_number',
        'status',
    ];

    public function releasedItems()
{
    return $this->hasMany(ReleasedItem::class);
}

public function salesReleases()
{
    return $this->hasMany(SalesRelease::class, 'customer_id');
}

}
