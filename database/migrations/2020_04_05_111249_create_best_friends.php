<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBestFriends extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('best_friends', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('friend_1')->unsigned();
            $table->bigInteger('friend_2')->unsigned();
            $table->timestamps();

            $table->foreign('friend_1')->references('id')->on('friends');
            $table->foreign('friend_2')->references('id')->on('friends');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('best_friends');
    }
}
