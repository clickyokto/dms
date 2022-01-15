<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInvoiceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invoice', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('invoice_code');
            $table->integer('customer_id')->nullable();
            $table->date('invoice_date');
            $table->char('status', 4);
            $table->double('sub_total')->default(0);
            $table->text('remarks')->nullable();
            $table->integer('tax_id')->nullable();
            $table->integer('discount')->default(0);
            $table->string('discount_type')->default('P');
            $table->double('total')->default(0);
            $table->double('paid_amount')->nullable();
            $table->double('returned_amount')->default(0);
            $table->double('balance')->nullable();
            $table->integer('ref_id')->nullable();
            $table->char('type', 2)->nullable();
            $table->char('cash_sell',1)->comment('1/0')->default('0');
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
        Schema::dropIfExists('invoice');
    }
}
