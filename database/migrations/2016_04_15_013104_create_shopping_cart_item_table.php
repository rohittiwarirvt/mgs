<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateShoppingCartItemTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shopping_cart_items', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('product_id');
            $table->integer('quote_id');
            $table->integer('attribute_id');
            $table->integer('option_id');
            $table->timestamps();
        });

        /*Copy of shopping cart items*/
        Schema::create('original_shopping_cart_items', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('product_id');
            $table->integer('quote_id');
            $table->integer('attribute_id');
            $table->integer('option_id');
            $table->float('option_value');
            $table->float('attribute_value');
            $table->float('product_value');
            $table->integer('quantity');
            $table->string('unit', 50);
            $table->double('discount', 8,2);
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
        Schema::drop('shopping_cart_items');
        Schema::drop('original_shopping_cart_items');
    }
}
