<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAddressTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('address', function (Blueprint $table) {
            $table->increments('id');
            $table->char('user_type', 1)->comment('P = Prasanal , S = Supllier');
             $table->char('address_type', 1)->comment('S = Shipping , B = Billing');
            $table->integer('ref_id');
            $table->string('address_line_1');
             $table->string('address_line_2');
            $table->integer('city_id');
            $table->integer('district_id');
             $table->string('postal_code')->nullable();
            $table->char('country',10);
            $table->text('description');
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
        Schema::dropIfExists('address');
    }
}
