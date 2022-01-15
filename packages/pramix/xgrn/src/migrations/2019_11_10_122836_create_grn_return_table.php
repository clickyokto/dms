<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGrnReturnTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('grn_return', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('grn_return_code');
            $table->integer('supplier_id')->nullable();
            $table->integer('grn_id')->nullable();
            $table->date('grn_return_date');
            $table->char('status', 4);
            $table->double('sub_total')->default(0);
            $table->double('nbt_amount')->nullable();
            $table->double('vat_amount')->nullable();
            $table->integer('discount')->default(0);
            $table->string('discount_type')->default('P');
            $table->double('total')->default(0);
            $table->double('paid_amount')->nullable();
            $table->double('balance')->nullable();
            $table->text('remarks')->nullable();
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
        Schema::dropIfExists('grn_return');
    }
}
