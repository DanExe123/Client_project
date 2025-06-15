<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('customer_returns', function (Blueprint $table) {
            $table->string('return_type')->nullable()->after('id'); // or replace 'id' with the appropriate column
        });
    }

    public function down(): void
    {
        Schema::table('customer_returns', function (Blueprint $table) {
            $table->dropColumn('return_type');
        });
    }
};