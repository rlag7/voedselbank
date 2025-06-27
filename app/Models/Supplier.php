<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Facades\Log;

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

    public function products(): BelongsToMany
    {
        try {
            return $this->belongsToMany(Product::class, 'product_supplier')
                ->withPivot('stock_quantity', 'last_delivery_date');
        } catch (\Exception $e) {
            // Log the exception and return an empty relation
            Log::error('Failed to load supplier products: ' . $e->getMessage());
            return $this->belongsToMany(Product::class, 'product_supplier')->whereRaw('1 = 0');
        }
    }
}
