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
        Schema::create('purchase_orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('supplier_id')->constrained()->onDelete('cascade'); // Links to the suppliers table
            $table->string('receipt_type'); // e.g., DR, INVOICE
            $table->date('order_date'); // Date of the purchase order
            $table->string('po_number')->unique()->nullable(); // Optional: A unique PO number
            $table->decimal('total_amount', 12, 2)->default(0); // Calculated total of all items
            $table->text('remarks')->nullable(); // Remarks for the PO
            $table->string('status')->default('Pending'); // e.g., Pending, Completed, Cancelled
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchase_orders');
    }
};