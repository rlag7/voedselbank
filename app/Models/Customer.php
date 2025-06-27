<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $fillable = [
        'person_id', 'number_of_adults', 'number_of_children', 'number_of_babies',
        'is_vegan', 'is_vegetarian', 'no_pork'
    ];

    public function person()
    {
        return $this->belongsTo(Person::class);
    }

    public function address()
    {
        return $this->hasOne(Address::class);
    }

    public function allergies()
    {
        return $this->belongsToMany(Allergy::class, 'customer_allergy');
    }

    public function foodPackages()
    {
        return $this->hasMany(FoodPackage::class);
    }
}
