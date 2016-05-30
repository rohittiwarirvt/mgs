<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateQuotesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('quotes', function (Blueprint $table) {
            $table->integer('quote_price')->nullable()->after('end_date');
            $table->string('appointment_time')->nullable()->after('appointment_date');
            $table->dropColumn('quote_type');
            $table->dropColumn('quote_inventory_info');
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


