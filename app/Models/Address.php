<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    protected $fillable = ['customer_id', 'street', 'house_number', 'postal_code', 'city'];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
}
