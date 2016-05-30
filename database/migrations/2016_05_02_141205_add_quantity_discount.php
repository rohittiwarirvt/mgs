<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddQuantityDiscount extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('shopping_cart_items', function (Blueprint $table) {
            $table->integer('quantity');
            $table->string('unit');
            $table->float('discount');
        });

        Schema::table('attributes', function (Blueprint $table) {
            $table->float('discount');
            $table->float('max_discount');
        });

        Schema::table('products', function (Blueprint $table) {
            $table->float('discount');
            $table->float('max_discount');
        });

        Schema::table('options', function (Blueprint $table) {
            $table->float('discount');
            $table->float('max_discount');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

    }
}
