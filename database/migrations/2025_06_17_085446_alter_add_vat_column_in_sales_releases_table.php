<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('sales_releases', function (Blueprint $table) {
            $table->decimal('add_vat', 10, 2)->change();
        });
    }

    public function down(): void
    {
        Schema::table('sales_releases', function (Blueprint $table) {
            // Optionally revert to previous type if known, e.g.:
            // $table->decimal('add_vat', 5, 2)->change();
        });
    }
};

