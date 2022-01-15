<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePurchaseOrderPaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purchase_order_payments', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('payment_code');
            $table->integer('branch_id')->nullable();
            $table->integer('purchase_order_id');
            $table->date('payment_date');
            $table->char('payment_method');
            $table->string('payment_ref_no')->nullable();
            $table->text('payment_remarks')->nullable();
            $table->double('payment_amount');
            $table->char('status', 1)->default(1);
            $table->integer('created_by');
            $table->integer('updated_by');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('purchase_order_payments');
    }
}
