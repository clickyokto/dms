<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('emails', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->char('email_type' , 3)->nullable();
            $table->integer('ref_id')->nullable();
            $table->text('subject');
            $table->string('email');
            $table->text('mail_body');
            $table->json('attachments')->nullable();
            $table->dateTime('send_time');
            $table->dateTime('sent_time')->nullable();
            $table->char('status');
            $table->integer('created_by');
            $table->integer('updated_by');
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
        Schema::dropIfExists('emails');
    }
}
