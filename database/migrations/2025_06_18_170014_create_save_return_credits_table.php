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
        Schema::create('save_return_credits', function (Blueprint $table) {
            $table->id();
            $table->foreignId('return_id')->constrained('customer_returns')->onDelete('cascade');
            $table->foreignId('customer_id')->constrained('customers')->onDelete('cascade');
            $table->string('product_barcode');
            $table->string('product_description');
            $table->integer('quantity');
            $table->decimal('unit_price', 10, 2);
            $table->decimal('subtotal', 10, 2); // Full subtotal of the returned item record
            $table->foreignId('released_item_id')->nullable()->constrained('released_items')->onDelete('set null'); // Link to the released item that was reduced
            $table->decimal('applied_amount', 10, 2)->default(0.00); // The actual amount applied in this specific transaction
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('save_return_credits');
    }
};
