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
            $table->id('id');
            $table->unsignedInteger('user_id');
            $table->unsignedInteger('team_id');
            $table->string('role',30)->nullable();
            $table->timestamps();

            //$table->foreign('user_id')->references('id')->on('user');
            //$table->foreign('team_id')->references('id')->on('teams');
        });
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
