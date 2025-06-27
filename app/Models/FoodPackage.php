<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FoodPackage extends Model
{
    protected $fillable = [
        'customer_id',
        'composition_date',
        'distribution_date',
        'is_active',  // moet hier ook in staan!
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
}
