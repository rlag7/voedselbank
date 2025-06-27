<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FoodPackage extends Model
{
    protected $fillable = ['customer_id', 'composition_date', 'distribution_date'];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function products()
    {
        return $this->belongsToMany(Product::class, 'food_package_product')
            ->withPivot('quantity');
    }
}
