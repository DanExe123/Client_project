<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExpensesTable extends Migration
{
    public function up(): void
    {
        Schema::create('expenses_table', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->string('category');
            $table->string('payee');
            $table->string('description')->nullable();
            $table->decimal('amount', 10, 2);
            $table->string('paid_by');
            $table->string('remarks')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('expenses_table');
    }
}
