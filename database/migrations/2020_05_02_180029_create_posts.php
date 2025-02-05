<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePosts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('posts', function (Blueprint $table) {
            $table-> bigIncrements("id");
            $table->bigInteger("author_id")->unsigned();
            $table->string("title");
            $table->text("content");
            $table->string("image")->nullable();
            $table->boolean("public")->default(0);
            $table->bigInteger("event_id")->unsigned()->nullable();
            $table->timestamps();
        });

        Schema::table('posts', function (Blueprint $table) {
           $table->foreign('author_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('posts');
    }
}
