<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateChequeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cheque', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('customer_id');
            $table->date('payment_date');
            $table->string('payment_ref_no')->nullable();
            $table->text('payment_remarks')->nullable();
            $table->double('payment_amount');
            $table->char('status',2);
            $table->date('cheque_date');
            $table->integer('bank_id');
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
        Schema::dropIfExists('cheque');
    }
}
