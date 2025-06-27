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
        Schema::create('products', function (Blueprint $table) {
            $table->id();

            $table->foreignId('category_id')
                ->constrained('product_categories')
                ->onDelete('cascade');

            $table->string('name', 100)->unique();
            $table->string('ean', 13)->unique(); // barcode
            $table->unsignedInteger('stock_quantity')->default(0);

            $table->string('soortalergie')->nullable();
            $table->date('houdbaarheiddatum')->nullable();
            $table->text('omschrijving')->nullable();
            $table->enum('status', ['actief', 'inactief'])->default('actief');
            $table->boolean('isactief')->default(true);
            $table->text('opmerking')->nullable();

            $table->timestamps(); // datumaangemaakt + datumsigewijzig
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
