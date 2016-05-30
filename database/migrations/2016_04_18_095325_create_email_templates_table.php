<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEmailTemplatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('email_templates', function (Blueprint $table) {
            $table->increments('id');
            $table->longText('template_body');
            $table->string('subject');
            $table->enum('type', array('registration', 'subscription','forgot-password', 'reset-password', 'quote-submit', 'quote-reject', 'job-success', 'note-reply','note-post','note-post-admin', 'quote-publish', 'enquiry', 'quote-buy'));
            $table->enum('status', array('0', '1'))->default(1);
            $table->integer('created_by');
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
        Schema::drop('email_templates');
    }
}
