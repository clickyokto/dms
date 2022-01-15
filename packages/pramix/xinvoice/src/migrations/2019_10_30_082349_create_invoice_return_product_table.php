<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInvoiceReturnProductTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invoice_return_product', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('invoice_return_id');
            $table->integer('product_id');
            $table->text('description')->nullable();
            $table->integer('qty')->nullable();
            $table->double('unit_price')->nullable();
            $table->integer('discount')->nullable();
            $table->string('discount_type')->default('percentage');
            $table->double('sub_total')->nullable();
            $table->boolean('discarded')->default(false);
            $table->string('type')->nullable();
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
        Schema::dropIfExists('invoice_return_product');
    }
}
