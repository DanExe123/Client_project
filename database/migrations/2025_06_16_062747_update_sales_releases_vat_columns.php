<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateSalesReleasesVatColumns extends Migration
{
    public function up()
    {
        Schema::table('sales_releases', function (Blueprint $table) {
            // Rename vat_amount to amount_net_of_vat
            $table->renameColumn('vat_amount', 'amount_net_of_vat');

            // Add add_vat column with no default
            $table->decimal('add_vat', 5, 2)->nullable();
        });
    }

    public function down()
    {
        Schema::table('sales_releases', function (Blueprint $table) {
            $table->renameColumn('amount_net_of_vat', 'vat_amount');
            $table->dropColumn('add_vat');
        });
    }
}
