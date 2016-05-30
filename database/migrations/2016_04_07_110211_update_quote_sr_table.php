<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateQuoteSrTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('quotes', function (Blueprint $table) {
            $table->string('created_by',50)->change();
            $table->string('updated_by',50)->change();

        });

        Schema::table('service_request', function (Blueprint $table) {
            $table->string('created_by',50)->change();
            $table->string('updated_by',50)->change();
            $table->renameColumn('technician_id','technician_info')->nullable()->change();
        });

          Schema::table('service_request', function (Blueprint $table) {
            $table->string('technician_info')->change();
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
        Schema::drop('service_request');
    }
}
