<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRefundToInvoiceReturnTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('invoice_return', function (Blueprint $table) {
            $table->double('refund')->default(0)->after('balance');
            $table->double('customer_credit')->default(0)->after('refund');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('invoice_return', function (Blueprint $table) {
            //
        });
    }
}
