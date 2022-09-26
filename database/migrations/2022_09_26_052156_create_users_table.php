<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password');
            $table->string('position');
            $table->bigInteger('state_id')->unsigned();
            $table->bigInteger('district_id')->unsigned();
            $table->bigInteger('township_id')->unsigned();
            $table->rememberToken();
            $table->timestamps();
            $table->foreign('state_id')
                ->references('id')->on('states')
                ->onDelete('cascade');
            $table->foreign('district_id')
                ->references('id')->on('districts')
                ->onDelete('cascade');
            $table->foreign('township_id')
                ->references('id')->on('townships')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
