<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePurchaseOrderTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purchase_order', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('branch_id')->nullable();
            $table->string('purchase_order_code');
            $table->integer('supplier_id');
            $table->date('purchase_order_date');
            $table->char('status', 4);
            $table->double('sub_total')->default(0);
            $table->text('remarks')->nullable();
            $table->double('nbt_amount')->nullable();
            $table->double('vat_amount')->nullable();
            $table->integer('discount')->default(0);
            $table->string('discount_type')->default('P');
            $table->double('total')->default(0);
            $table->double('paid_amount')->nullable();
            $table->double('balance')->nullable();
            $table->integer('ref_id')->nullable();
            $table->char('type', 2)->nullable();
            $table->integer('assigned_user')->nullable();
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
        Schema::dropIfExists('purchase_order');
    }
}
