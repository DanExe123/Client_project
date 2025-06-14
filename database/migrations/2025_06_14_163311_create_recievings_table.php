<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRecievingsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('recievings', function (Blueprint $table) {
            $table->id();

            // Foreign key to PurchaseOrderItem
            $table->foreignId('purchase_order_item_id')
                  ->constrained()
                  ->onDelete('cascade');

            $table->string('status')->default('pending'); // e.g., pending, received, cancelled

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('recievings');
    }
}
