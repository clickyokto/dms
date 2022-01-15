<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCustomerTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customer', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->char('title', 8);
            $table->string('business_name');
            $table->char('customer_type', 1)->nullable();
            $table->string('fname');
            $table->string('lname');
            $table->string('nic');
            $table->string('passport_no')->nullable();
            $table->string('passport_expire_date')->nullable();
            $table->string('telephone');
            $table->string('mobile');
            $table->string('email');
            $table->string('dob')->nullable();
            $table->char('gender', 1);
            $table->string('website');
            $table->text('remarks');
            $table->double('balance');
            $table->double('credit');
            $table->integer('discount');
            $table->string('discount_type');
            $table->char('status', 1);
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
        Schema::dropIfExists('customer');
    }
}
