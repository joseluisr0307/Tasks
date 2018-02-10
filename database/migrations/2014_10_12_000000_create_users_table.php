<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

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
            $table->increments('id');
            $table->string('name',60);
            $table->string('last_name',60);
            $table->integer('type_document')->unsigned();
            $table->string('number_document',60);
            $table->string('email',60)->unique();
            $table->string('password');
            $table->string('patch_photo')->nullable();
            $table->rememberToken();
            $table->timestamps();

            $table->foreign('type_document')->references('id')->on('documentos')->onUpdate('cascade');
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
