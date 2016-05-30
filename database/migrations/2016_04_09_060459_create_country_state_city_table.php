<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCountryStateCityTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
         Schema::create('country', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 100);
            $table->boolean('is_active');
            $table->timestamps();
            $table->integer('created_by');
            $table->integer('updated_by');
        });

        Schema::create('state', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('country_id')->unsigned();
            $table->string('name', 100);
            $table->boolean('is_active');
            $table->timestamps();
            $table->integer('created_by');
            $table->integer('updated_by');
        }); 

        Schema::create('city', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('state_id')->unsigned();
            $table->string('name',100);
            $table->integer('pincode');
            $table->boolean('is_active');
            $table->timestamps();
            $table->integer('created_by');
            $table->integer('updated_by');
        }); 
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('country');
        Schema::drop('state');
        Schema::drop('city');
    }
}
