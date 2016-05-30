<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNotesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('notes', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users');
            $table->integer('quote_id')->unsigned();
            $table->foreign('quote_id')->references('id')->on('quotes');
            $table->integer('department_id')->unsigned();
            $table->foreign('department_id')->references('id')->on('department');
            $table->integer('subject_id')->unsigned();
            $table->foreign('subject_id')->references('id')->on('subject');
            $table->integer('parent_id');
            $table->string('message');
            $table->enum('note_type', array('Internal', 'External'))->default('External');
            $table->enum('status', array('Open', 'Close'))->default('Open');
            $table->timestamps();
            $table->integer('created_by');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('notes');
    }
}
