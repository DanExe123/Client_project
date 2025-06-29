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
        Schema::create('customer_purchase_order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('purchase_order_id')->constrained('customer_purchase_orders')->onDelete('cascade'); // Links to the purchase_orders table
            $table->foreignId('product_id')->constrained('products')->onDelete('restrict'); // Links to the products table
            $table->string('product_description'); // Store description at time of PO creation (for historical accuracy)
            $table->string('product_barcode')->nullable(); // Store barcode for historical accuracy
            $table->integer('quantity');
            $table->decimal('unit_price', 10, 2); // Price at the time of purchase
            $table->decimal('product_discount', 10, 2)->default(0); // Add product discount here
            $table->decimal('subtotal', 10, 2); // quantity * unit_price
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customer_purchase_order_items');
    }
};
