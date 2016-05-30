<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateUserRolesSchema extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('first_name');
            $table->string('last_name');
            $table->enum('user_type', ['guest','registered', 'internal', 'external'])->default('registered');
            $table->timestamp('last_access');
        });


        // role table
        //
        Schema::table('roles', function (Blueprint $table) {
//            $table->renameColumn('name', 'role_name');
            $table->tinyInteger('is_builtin');
            $table->integer('created_by')->default(0);
            $table->integer('updated_by')->default(0);
        });

        // permission name
        Schema::table('permissions', function (Blueprint $table) {
  //          $table->renameColumn('name', 'permission_name');
            $table->tinyInteger('is_builtin');
            $table->integer('created_by')->default(0);
            $table->integer('updated_by')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

        Schema::table('roles', function (Blueprint $table) {
          // $table->renameColumn('rid','id');
          $table->renameColumn('role_name', 'name');
        });

        Schema::table('permissions', function (Blueprint $table) {
          // $table->renameColumn('rid','id');
          $table->renameColumn('permission_name', 'name');
        });
    }
}
