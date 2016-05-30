<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateServicerequestTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('service_request', function (Blueprint $table) {
            $table->integer('id')->references('id')->on('quotes');
            $table->integer('user_id')->references('id')->on('users');
            $table->string('user_info')->nullable();
            $table->string('service_info')->nullable();
            $table->timestamp('appointment_date')->nullable();
            $table->string('appointment_time')->nullable();
            $table->integer('sr_price')->nullable();
            $table->timestamp('end_date')->nullable();
            $table->integer('status_id')->references('id')->on('status');
            $table->timestamp('expire_date')->nullable();
            $table->integer('source_id')->references('id')->on('quote_sources');
            $table->integer('technician_id')->nullable();
            $table->integer('progress_status')->nullable();
            $table->string('payment_status')->nullable();
            $table->timestamps();
            $table->integer('created_by')->default(0);
            $table->integer('updated_by')->default(0);
        });
    }

   

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('service_request');
    }
}
