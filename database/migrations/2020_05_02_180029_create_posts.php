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
            $table->string("author", 50);
            $table->text("content");
            $table->string("image")->nullable();
            $table->boolean("public")->default(0);
            $table->timestamps();
        });

        Schema::table('posts', function (Blueprint $table) {
          //  $table->foreign('team_id')->references('id')->on('teams');
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
