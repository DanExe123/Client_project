<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
class CreatePaymentsTable extends Migration
{
    public function up()
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->foreignId('supplier_id')->constrained()->onDelete('cascade');

            $table->string('payment_method'); // Cash, Check, Bank Transfer
            $table->string('bank')->nullable(); // Used for Check or Bank Transfer
            $table->string('cheque_number')->nullable(); // For Check
            $table->date('check_date')->nullable(); // For Check
            $table->string('reference_number')->nullable(); // For Bank Transfer
            $table->date('transaction_date')->nullable(); // For Bank Transfer

            $table->decimal('total_amount', 15, 2)->default(0); // Total of receivings selected
            $table->decimal('amount_paid', 15, 2)->default(0); // Final payment after returns/deductions
            $table->decimal('ewt_amount', 15, 2)->default(0);
            $table->decimal('deduction', 15, 2)->default(0);
            $table->text('remarks')->nullable();

            $table->json('received_item_ids')->nullable(); // IDs of receiving records

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('payments');
    }
}
