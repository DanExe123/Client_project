<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateProductsAddGoodAndDamagesColumns extends Migration
{
    public function up()
    {
        Schema::table('products', function (Blueprint $table) {
            if (!Schema::hasColumn('products', 'good')) {
                $table->string('good')->default('0')->after('selling_price');
            }
            if (!Schema::hasColumn('products', 'damages')) {
                $table->string('damages')->default('0')->after('good');
            }
        });
    }

    public function down()
    {
        Schema::table('products', function (Blueprint $table) {
            if (Schema::hasColumn('products', 'good')) {
                $table->dropColumn('good');
            }
            if (Schema::hasColumn('products', 'damages')) {
                $table->dropColumn('damages');
            }
        });
    }
}
;
