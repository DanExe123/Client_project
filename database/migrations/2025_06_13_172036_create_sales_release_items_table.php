<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('sales_release_items', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('sales_release_id');
            $table->unsignedBigInteger('product_id');
            $table->string('product_description');
            $table->string('product_barcode')->nullable();
            $table->integer('quantity');
            $table->decimal('unit_price', 10, 2);
            $table->decimal('discount', 10, 2)->default(0)->nullable();
            $table->decimal('subtotal', 12, 2);
            $table->timestamps();

            $table->foreign('sales_release_id')->references('id')->on('sales_releases')->onDelete('cascade');
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('sales_release_items');
    }
};