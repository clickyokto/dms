<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGrnReturnProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('grn_return_products', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('grn_return_id');
            $table->integer('product_id');
            $table->text('description')->nullable();
            $table->integer('qty')->nullable();
            $table->double('unit_price')->nullable();
            $table->double('sub_total')->nullable();
            $table->boolean('discarded')->default(false);
            $table->integer('status');
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
        Schema::dropIfExists('grn_return_products');
    }
}
