<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSaveReturnCreditTable extends Migration
{
    public function up(): void
    {
        Schema::create('save_return_credit', function (Blueprint $table) {
            $table->id();
            $table->foreignId('return_id')->constrained('customer_returns')->onDelete('cascade');
            $table->foreignId('customer_id')->constrained('customers')->onDelete('cascade');
            $table->date('order_date');
            $table->string('product_barcode');
            $table->string('product_description');
            $table->integer('quantity');
            $table->decimal('unit_price', 10, 2);
            $table->decimal('subtotal', 10, 2);
        
  
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('save_return_credit');
    }
}
