<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Tarea extends Model
{
    use SoftDeletes;
    //
    protected $fillable = ['nombre_tarea','fecha_inicio','fecha_finalizacion','usuario'];

    protected $dates = ['deleted_at'];
    
    public function archivos(){

        return $this->hasMany('App\Archivo','tarea','id');

    }
    
    public function usuario(){

        return $this->belongsTo('App\User','usuario');
        

    }

}
