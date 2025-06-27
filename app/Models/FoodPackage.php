<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FoodPackage extends Model
{
    protected $fillable = [
        'customer_id',
        'composition_date',
        'distribution_date',
        'is_active',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    // ✅ Many-to-many relatie met producten
    public function products()
    {
        return $this->belongsToMany(Product::class)
            ->withPivot('quantity')
            ->withTimestamps();
    }
}
