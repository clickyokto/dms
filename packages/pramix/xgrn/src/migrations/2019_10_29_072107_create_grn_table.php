<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGrnTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
       // Schema::dropIfExists('purchase_order');
      //  Schema::dropIfExists('purchase_order_products');

        Schema::create('grn', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('branch_id')->nullable();
            $table->string('grn_code');
            $table->integer('supplier_id');
            $table->date('grn_date');
            $table->integer('purchase_order_id');
            $table->integer('approved_by')->nullable();
            $table->string('delivery_location')->nullable();
            $table->text('remarks');
            $table->char('status', 2);
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
        Schema::dropIfExists('grn');
    }
}
