<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDepartmentSubjectTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('department', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title',100);
            $table->boolean('is_active');
            $table->timestamps();
            $table->integer('created_by');
            $table->integer('updated_by');
            //Display For: 1=Customer  2=Admin 3=Both
            $table->integer('display');
        });

        Schema::create('subject', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title', 100);
            $table->boolean('is_active');
            $table->timestamps();
            $table->integer('created_by');
            $table->integer('updated_by');
            //Display For: 1=Customer  2=Admin 3=Both
            $table->integer('display');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('department');
        Schema::drop('subject');
    }
}
