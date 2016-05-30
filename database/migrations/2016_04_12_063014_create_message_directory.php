<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMessageDirectory extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('message_directory', function (Blueprint $table) {
            $table->increments('id');
            $table->string('sender', 15);
            $table->string('receiver', 15);
            $table->string('sms_type', 100);
            $table->string('sms_body');
            $table->enum('status', array('0','1'));
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
        Schema::drop('message_directory');
    }
}
