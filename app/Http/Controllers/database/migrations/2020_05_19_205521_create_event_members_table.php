<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEventMembersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('event_members', function (Blueprint $table) {
            $table->id('id');
            $table->unsignedInteger('user_id');
            $table->unsignedInteger('event_id');
            $table->boolean('decision')->default(0);
            $table->timestamps();

            //$table->foreign('user_id')->references('id')->on('user');
            //$table->foreign('event_id')->references('id')->on('event');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('event_members');
    }
}
