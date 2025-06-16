<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('receivings', function (Blueprint $table) {
            $table->id();
            $table->string('po_number');
            $table->foreignId('supplier_id')->constrained()->onDelete('cascade');
            $table->string('receipt_type');
            $table->date('order_date');
            $table->decimal('purchase_discount', 8, 2)->default(0);
            $table->decimal('grand_total', 12, 2)->default(0);
            $table->text('remarks')->nullable();
            $table->enum('status', ['approved'])->default('approved');
            $table->unsignedBigInteger('approved_by')->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('receivings');
    }
};
