<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTeamMembersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('team__members', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('team_id');
            $table->string('role',20)->nullable();
            $table->timestamps();
        });
      //  Schema::create('team__members', function (Blueprint $table){

            //$table->foreign('user_id')->references('id')->on('user');
            //$table->foreign('team_id')->references('id')->on('teams');
      //  });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('team__members');
    }
}
