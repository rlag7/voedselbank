<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('suppliers', function (Blueprint $table) {
            $table->id();
            $table->string('company_name', 100);
            $table->string('address', 200)->nullable();
            $table->string('contact_name', 100);
            $table->string('contact_email', 100);
            $table->string('phone', 20)->nullable();
            $table->enum('supplier_type', [
                'supermarkt',
                'groothandel',
                'boer',
                'instelling',
                'overheid',
                'particulier',
            ]);
            $table->string('supplier_number', 20)->unique();

            // ✅ Extra kolommen volgens jouw wensen
            $table->string('product_name')->nullable();
            $table->integer('stock_quantity')->default(0);
            $table->date('last_delivery_date')->nullable();
            $table->boolean('is_active')->default(false);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('suppliers');
    }
};
