<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Updatetaxtables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shopping_materials', function (Blueprint $table) {
            $table->increments('id');
            $table->string('material_name');
            $table->integer('material_quantity')->nullable();
            $table->float('unit_price')->nullable();
            $table->float('material_total')->nullable();
            $table->integer('quote_id');
            $table->timestamps();
        });

        Schema::table('quotes', function (Blueprint $table) {
            $table->float('vat')->nullable();
            $table->float('service_tax')->nullable();
            $table->float('labour_charges')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('shopping_materials');
    }
}
