<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('payment_invoices', function (Blueprint $table) {
            $table->id();
            
             $table->foreignId('customer_id')->constrained('customers')->onDelete('cascade');
            $table->foreignId('sales_release_id')->constrained('sales_releases')->onDelete('cascade');

            $table->string('invoice_number'); // e.g., INV-0001
            $table->date('invoice_date');
            $table->decimal('invoice_amount', 12, 2);
            $table->decimal('amount', 12, 2);
            $table->decimal('deduction', 12, 2)->nullable();
            $table->decimal('ewt_amount', 12, 2)->nullable();  
            $table->string('remarks')->nullable();
    
            $table->string('payment_method')->nullable(); // Cash, Cheque, etc.
            $table->string('bank')->nullable();
            $table->string('cheque_number')->nullable();
            $table->date('check_date')->nullable();
            $table->string('reference_number')->nullable();
            $table->date('transaction_date')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payment_invoices');
    }
};
