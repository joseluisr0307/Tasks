<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Eventos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('eventos', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('usuario')->unsigned();
            $table->string('registro',200);
            $table->string('accion',20);
            $table->timestamps();

            $table->foreign('usuario')->references('id')->on('users')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
