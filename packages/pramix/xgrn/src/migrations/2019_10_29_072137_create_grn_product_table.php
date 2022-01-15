<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGrnProductTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('grn_product', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('grn_id');
            $table->integer('product_id');
            $table->text('description')->nullable();
            $table->integer('ordered_qty');
           $table->integer('delivered_qty');
            $table->double('unit_price');
//            $table->double('sub_total');
            $table->char('status',2);
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
        Schema::dropIfExists('grn_product');
    }
}
