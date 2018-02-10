<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Archivo extends Model
{
    //

    protected $fillable = [
        'tarea', 'ruta','nombre_archivo','tipo',
    ];

   public function tarea(){

        return $this->belongsTo('App\Tarea','tarea');

    }

}
