<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSeodata extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('seodata', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('webpage_id');
            $table->integer('version');
            $table->string('title', 100);
            $table->text('meta_description', 1000);
            $table->text('meta_keyword', 1000);
            $table->string('page_type', 100);
            $table->string('canonical', 500);
            $table->text('seocopy_block', 5000);
            $table->string('robots', 100);
            $table->string('authorship', 200);
            $table->string('opengraph_type', 30);
            $table->string('opengraph_title', 1000);
            $table->string('opengraph_description', 1000);
            $table->string('opengraph_url', 500);
            $table->string('opengraph_image', 500);
            $table->string('twitter_card', 30);
            $table->string('twitter_title', 100);
            $table->string('twitter_description', 1000);
            $table->string('twitter_image', 500);
            $table->string('twitter_url', 500);
            $table->string('schema_type', 30);
            $table->string('schema_name', 200);
            $table->string('schema_description', 3000);
            $table->string('schema_image', 500);
            $table->string('updated_by', 30);
            $table->string('created_by', 30);
            $table->string('H1', 100);
            $table->integer('community_id');
            $table->enum('is_active', array('0', '1'));
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
        Schema::drop('seodata');
    }
}
