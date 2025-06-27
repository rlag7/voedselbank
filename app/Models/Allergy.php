<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Allergy extends Model
{
    protected $fillable = ['name', 'description', 'risk'];

    public function customers()
    {
        return $this->belongsToMany(Customer::class, 'customer_allergy');
    }
}
