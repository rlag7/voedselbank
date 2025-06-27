<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    protected $fillable = [
        'company_name',
        'address',
        'contact_name',
        'contact_email',
        'phone',
        'supplier_type',
        'supplier_number',
        'product_name',
        'stock_quantity',
        'last_delivery_date',
        'is_active',
    ];

    public function products()
    {
        return $this->belongsToMany(Product::class, 'product_supplier')
            ->withPivot('stock_quantity', 'last_delivery_date');
    }
}

