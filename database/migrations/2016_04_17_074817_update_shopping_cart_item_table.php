<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateShoppingCartItemTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('shopping_cart_items', function (Blueprint $table) {
            $table->integer('option_value');
            $table->integer('attribute_value');
            $table->integer('product_value');
        });

        Schema::table('status', function (Blueprint $table) {
            $table->string('status_internal_name');
            $table->string('status_external_name');
            $table->dropColumn('status_title');
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
