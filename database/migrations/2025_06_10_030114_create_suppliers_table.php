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
        Schema::create('suppliers', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Supplier Name
            $table->string('email')->unique();
            $table->string('address')->nullable();
            $table->string('term')->nullable(); // Terms (No. of Days)
            $table->string('tin_number')->nullable(); // Tax Identification Number
            $table->string('contact', 15); // Store 11-digit number as string with room for format
            $table->string('contact_person')->nullable();
            $table->boolean('status')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('suppliers');
    }
};
