<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddStoreIdToGrnProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('grn_product', function (Blueprint $table) {
            Schema::table('grn_product', function (Blueprint $table) {
                $table->integer('store_id')->after('status')->nullable();
            });
            Schema::table('grn_return_products', function (Blueprint $table) {
                $table->integer('store_id')->after('status')->nullable();
            });
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('grn_product', function (Blueprint $table) {
            //
        });
    }
}
