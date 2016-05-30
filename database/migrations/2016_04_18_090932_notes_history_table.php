<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class NotesHistoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('notes_history', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id');
            $table->integer('note_id')->unsigned();
            $table->foreign('note_id')->references('id')->on('notes');
            $table->integer('quote_id');
            $table->integer('parent_id');
            $table->enum('status', array('Open', 'Close'));
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
        Schema::drop('notes_history');
    }
}
