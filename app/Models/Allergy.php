<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log; 
use Illuminate\Database\QueryException;

class Allergy extends Model
{
    protected $fillable = ['name', 'description', 'risk', 'is_actief'];

    // Relatie met klanten (veel-op-veel)
    public function customers()
    {
        try {
            // Probeer klanten op te halen die aan de allergie gekoppeld zijn
            return $this->belongsToMany(Customer::class, 'customer_allergy');
        } catch (QueryException $e) {
            // Log de fout en retourneer een lege collectie of een foutmelding
            Log::error("Fout bij het ophalen van klanten voor allergie: " . $e->getMessage());
            return collect();  // Lege collectie om geen errors te geven
        }
    }

    // Eventuele andere logica of methoden voor je model
}
