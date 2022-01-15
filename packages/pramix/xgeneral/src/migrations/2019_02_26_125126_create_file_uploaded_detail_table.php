<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFileUploadedDetailTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('file_uploaded_details', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->char('upload_type',10)->comment('P = Product / PC = Product Category / C = Customer');
            $table->integer('lines_total')->nullable();
            $table->integer('lines_succeeded')->nullable();
            $table->boolean('status');
            $table->integer('created_by')->nullable();
            $table->integer('updated_by')->nullable();
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
        Schema::dropIfExists('file_uploaded_details');
    }
}
