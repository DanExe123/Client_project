<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('supplier_return_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('return_id')->constrained('supplier_returns')->onDelete('cascade'); // Links to the purchase_orders table
            $table->foreignId('product_id')->constrained('products')->onDelete('restrict'); // Links to the products table
            $table->string('product_description'); // Store description at time of PO creation (for historical accuracy)
            $table->string('product_barcode')->nullable(); // Store barcode for historical accuracy
            $table->integer('quantity');
            $table->decimal('unit_price', 10, 2); // Price at the time of purchase
            $table->decimal('subtotal', 10, 2); // quantity * unit_price
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('supplier_return_items');
    }
};
