<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = ['category_id', 'name', 'ean', 'stock_quantity'];

    public function category()
    {
        return $this->belongsTo(ProductCategory::class, 'category_id');
    }

    public function suppliers()
    {
        return $this->belongsToMany(Supplier::class, 'product_supplier')
            ->withPivot('stock_quantity', 'last_delivery_date');
    }

    public function foodPackages()
    {
        return $this->belongsToMany(FoodPackage::class, 'food_package_product')
            ->withPivot('quantity');
    }
}
