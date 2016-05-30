<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateAttributeSchema extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('attributes', function (Blueprint $table) {
            $table->string('display_type');
            $table->index(['attribute_description', 'price','attribute_name']);
        });

         Schema::table('options', function (Blueprint $table) {
           $table->string('price')->nullable();
           $table->index(['option_description', 'price','option_name']);
           $table->string('display_type');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
