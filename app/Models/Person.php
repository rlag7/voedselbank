<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Person extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'email', 'phone'];

    public function user()
    {
        return $this->hasOne(User::class);
    }

    public function customer()
    {
        return $this->hasOne(Customer::class);
    }
}
