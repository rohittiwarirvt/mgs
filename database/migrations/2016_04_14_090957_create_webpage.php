<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;


class CreateWebpage extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('webpage', function (Blueprint $table) {
            $table->increments('id');
            $table->string('friendly_url', 500);
            $table->string('created_by', 30);
            $table->string('updated_by', 30);
            $table->enum('is_active', array('0', '1'));
            $table->string('webpage_id', 30);
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
        Schema::drop('webpage');
    }
}
