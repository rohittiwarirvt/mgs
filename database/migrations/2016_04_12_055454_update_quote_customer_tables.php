<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateQuoteCustomerTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('quotes', function (Blueprint $table) {
            $table->dropColumn('appointment_time');
        });

      Schema::table('users', function (Blueprint $table) {
            $table->string('customer_id')->nullable();
        });

        Schema::table('quotes', function (Blueprint $table) {
            $table->timestamp('appointment_time')->nullable()->after('appointment_date');
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
