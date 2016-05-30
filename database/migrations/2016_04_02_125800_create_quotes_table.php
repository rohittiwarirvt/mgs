<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateQuotesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('quotes', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->references('id')->on('users');
            $table->string('user_information')->nullable();
            $table->string('quote_service_info')->nullable();
            $table->string('quote_inventory_info')->nullable();
            $table->timestamp('appointment_date')->nullable();
            $table->timestamp('end_date');
            $table->integer('status_id')->references('id')->on('status');
            $table->timestamp('expire_date')->nullable();
            $table->integer('quote_type')->references('id')->on('quote_types');
            $table->integer('quote_source_id')->references('id')->on('quote_sources');
            $table->timestamps();
            $table->integer('created_by')->default(0);
            $table->integer('updated_by')->default(0);

        });


/*copy of quote */

          Schema::create('original_quotes', function (Blueprint $table) {
            $table->increments('id')->unsigned();
            $table->integer('user_id')->references('id')->on('users');
            $table->string('user_information')->nullable();
            $table->timestamp('appointment_date')->nullable();
            $table->timestamp('appointment_time')->nullable();
            $table->timestamp('end_date');
            $table->float('quote_price')->nullable();
            $table->integer('status_id')->references('id')->on('status');
            $table->timestamp('expire_date')->nullable();
            $table->integer('quote_source_id')->references('id')->on('quote_sources');
            $table->integer('created_by')->default(0);
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
        Schema::drop('quotes');
        Schema::drop('original_quotes');
    }
}
