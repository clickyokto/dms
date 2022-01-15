<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMediaTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('media', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('ref_id')->nullable();
            $table->string('media_type');
            $table->string('folder_name');
            $table->string('file_name');
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
    public function down() {
        Schema::dropIfExists('media');
    }

}
