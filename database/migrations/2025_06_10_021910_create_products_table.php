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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('barcode')->unique();
            $table->string('supplier')->nullable();
            $table->string('description');
            $table->string('quantity')->default(0);
            $table->string('highest_uom')->nullable();
            $table->string('lowest_uom')->nullable();
            $table->string('lowest_uom_quantity')->default(0);
            $table->decimal('price', 15, 2)->default(0);
            $table->decimal('selling_price', 15, 2)->default(0);
            $table->boolean('status')->default(true);
            $table->timestamps();
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