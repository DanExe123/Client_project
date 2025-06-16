<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('released_items', function (Blueprint $table) {
            $table->id();

            // From sales_releases
            $table->unsignedBigInteger('sales_release_id');
            $table->unsignedBigInteger('purchase_order_id')->nullable();
            $table->string('receipt_type');
            $table->unsignedBigInteger('customer_id');
            $table->date('release_date')->nullable();
            $table->decimal('discount', 10, 2)->nullable();
            $table->text('remarks')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->decimal('vat_percent', 5, 2)->nullable();
            $table->decimal('total_amount', 15, 2)->nullable();
            $table->decimal('amount_net_of_vat', 15, 2)->nullable();
            $table->decimal('total_with_vat', 15, 2)->nullable();
            $table->timestamp('printed_at')->nullable();
            $table->boolean('add_vat')->default(false);

            // From sales_release_items
            $table->unsignedBigInteger('product_id');
            $table->text('product_description')->nullable();
            $table->string('product_barcode');
            $table->decimal('quantity', 10, 2);
            $table->decimal('unit_price', 10, 2);
            $table->decimal('discount_item', 10, 2)->nullable(); // renamed to avoid conflict
            $table->decimal('subtotal', 15, 2);

            $table->timestamps();

            // Foreign keys
            $table->foreign('sales_release_id')->references('id')->on('sales_releases')->onDelete('cascade');
            $table->foreign('purchase_order_id')->references('id')->on('customer_purchase_orders')->onDelete('set null');
            $table->foreign('customer_id')->references('id')->on('customers')->onDelete('cascade');
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
            $table->foreign('created_by')->references('id')->on('users')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('released_items');
    }
};
