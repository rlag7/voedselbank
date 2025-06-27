<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\FoodPackage;
use App\Models\Customer;
use Illuminate\Foundation\Testing\RefreshDatabase;

class FoodPackageReadTest extends TestCase
{
    use RefreshDatabase;

    public function test_food_packages_can_be_retrieved()
    {
        $customer = Customer::factory()->create();

        $foodPackage = FoodPackage::factory()->create([
            'customer_id' => $customer->id,
        ]);

        $retrieved = FoodPackage::with('customer')->find($foodPackage->id);

        $this->assertNotNull($retrieved);
        $this->assertEquals($customer->id, $retrieved->customer->id);
    }
}
