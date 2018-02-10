<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTareasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tareas', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('usuario')->unsigned();
            $table->string('nombre_tarea',60);
			$table->date('fecha_inicio');			
            $table->date('fecha_finalizacion');			
            $table->String('estado',15)->default('Planeada');
            $table->softDeletes();	
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
        Schema::dropIfExists('tareas');
    }
}
