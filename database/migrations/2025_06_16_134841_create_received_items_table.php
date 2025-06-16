<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('received_items', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('receiving_id');
            $table->string('po_number');
            $table->unsignedBigInteger('supplier_id');

            $table->string('receipt_type');
            $table->date('order_date')->nullable();
            $table->decimal('purchase_discount', 10, 2)->nullable();
            $table->decimal('grand_total', 15, 2)->default(0);
            $table->text('remarks')->nullable();
            $table->string('status')->default('Pending');

            $table->unsignedBigInteger('approved_by')->nullable();
            $table->timestamp('approved_at')->nullable();

            $table->string('barcode');
            $table->text('description')->nullable();
            $table->decimal('quantity', 10, 2)->default(0);
            $table->decimal('unit_price', 10, 2)->default(0);
            $table->decimal('discount', 10, 2)->default(0);
            $table->decimal('subtotal', 15, 2)->default(0);

            $table->timestamps();

            // Foreign keys
            $table->foreign('receiving_id')->references('id')->on('receivings')->onDelete('cascade');
            $table->foreign('supplier_id')->references('id')->on('suppliers')->onDelete('cascade');
            $table->foreign('approved_by')->references('id')->on('users')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('received_items');
    }
};
