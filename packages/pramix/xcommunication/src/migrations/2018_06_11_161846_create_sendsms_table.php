<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSendsmsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('send_sms', function (Blueprint $table) {
            $table->increments('id');
            $table->string('recipient_phone_no')->nullable();
            $table->integer('customer_id')->nullable();
            $table->dateTime('send_time');
            $table->text('message')->nullable();
            $table->boolean('status', 1)->comment('0 = Pending , 1 = Complete');
            $table->integer('created_by');
            $table->integer('updated_by');
            $table->integer('template_id')->nullable();
            $table->integer('type_id')->comment('Send SMS , Birthday Wish');
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
        Schema::dropIfExists('send_sms');
    }
}
