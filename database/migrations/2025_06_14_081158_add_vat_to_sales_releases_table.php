<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('sales_releases', function (Blueprint $table) {
            $table->decimal('vat_percent', 5, 2)->default(12);
            $table->decimal('vat_amount', 10, 2)->default(0);
            $table->decimal('total_with_vat', 12, 2)->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sales_releases', function (Blueprint $table) {
            //
        });
    }
};
