<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddBranchIdToAllTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('address', function (Blueprint $table) {
            $table->integer('branch_id')->after('id')->nullable();
        });
        Schema::table('average_cost_table', function (Blueprint $table) {
            $table->integer('branch_id')->after('id')->nullable();
        });
        Schema::table('customer', function (Blueprint $table) {
            $table->integer('branch_id')->after('id')->nullable();
        });
        Schema::table('emails', function (Blueprint $table) {
            $table->integer('branch_id')->after('id')->nullable();
        });
//        Schema::table('finance', function (Blueprint $table) {
//            $table->integer('branch_id')->after('id')->nullable();
//        });
//        Schema::table('finance_category', function (Blueprint $table) {
//            $table->integer('branch_id')->after('id')->nullable();
//        });
//        Schema::table('general_finance', function (Blueprint $table) {
//            $table->integer('branch_id')->after('id')->nullable();
//        });
        Schema::table('grn_product', function (Blueprint $table) {
            $table->integer('branch_id')->after('id')->nullable();
        });
        Schema::table('grn_return', function (Blueprint $table) {
            $table->integer('branch_id')->after('id')->nullable();
        });
        Schema::table('grn_return_products', function (Blueprint $table) {
            $table->integer('branch_id')->after('id')->nullable();
        });
        Schema::table('inventory', function (Blueprint $table) {
            $table->integer('branch_id')->after('id')->nullable();
        });
        Schema::table('invoice', function (Blueprint $table) {
            $table->integer('branch_id')->after('id')->nullable();
        });
        Schema::table('invoice_payments', function (Blueprint $table) {
            $table->integer('branch_id')->after('id')->nullable();
        });
        Schema::table('invoice_products', function (Blueprint $table) {
            $table->integer('branch_id')->after('id')->nullable();
        });
        Schema::table('invoice_recurring', function (Blueprint $table) {
            $table->integer('branch_id')->after('id')->nullable();
        });
        Schema::table('invoice_return', function (Blueprint $table) {
            $table->integer('branch_id')->after('id')->nullable();
        });
        Schema::table('invoice_return_product', function (Blueprint $table) {
            $table->integer('branch_id')->after('id')->nullable();
        });
        Schema::table('product', function (Blueprint $table) {
            $table->integer('branch_id')->after('id')->nullable();
        });
        Schema::table('product_categories', function (Blueprint $table) {
            $table->integer('branch_id')->after('id')->nullable();
        });
        Schema::table('product_discounts', function (Blueprint $table) {
            $table->integer('branch_id')->after('id')->nullable();
        });
//        Schema::table('quotation', function (Blueprint $table) {
//            $table->integer('branch_id')->after('id')->nullable();
//        });
//        Schema::table('quotation_products', function (Blueprint $table) {
//            $table->integer('branch_id')->after('id')->nullable();
//        });
//        Schema::table('supplier', function (Blueprint $table) {
//            $table->integer('branch_id')->after('id')->nullable();
//        });
        Schema::table('users', function (Blueprint $table) {
            $table->integer('branch_id')->after('id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            //
        });
    }
}
