<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Tarea;
use App\Archivo;
use App\Evento;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\CrearTareaRequest;
use App\Http\Requests\EditarTareaRequest;

class TareaController extends Controller
{
    function validarEstado(){

        $id_user  =  Auth::id();


        Tarea::where('fecha_inicio', '<=', date("Y-m-d"))
        ->where('fecha_finalizacion', '>=', date("Y-m-d"))
        ->where('usuario', '=', $id_user)
        ->update(['estado' => 'Iniciada']);

        Tarea::where('fecha_inicio', '<', date("Y-m-d"))
        ->where('fecha_finalizacion', '<', date("Y-m-d"))
        ->where('usuario', '=', $id_user)
        ->update(['estado' => 'Finalizada']);
    }

    public function index()
    {
        $this->validarEstado();
        $user  =  Auth::user();
        $tareas = $user->tareas();
        return \Response::json($tareas,200);
    }

    public function pagination($num_registros)
    {   
        try{
            $this->validarEstado();
            $user  =  Auth::user();
            $tareas = $user->tareas()->paginate($num_registros);

        }catch(\Exception $e){
            return \Response::json(
                ['status' => $e->getMessage()], 422
            );
        }
        return \Response::json($tareas,200);
    }
 
    public function show($id)
    {   
        try{
            $tarea =  Tarea::where('id_tarea', '=',$id);


        }catch(\Exception $e){
            return \Response::json(
                ['status' => $e->getMessage()], 422
            );
        }
        return $tarea;
    }
 
    public function update(EditarTareaRequest $request, $id)
    {
        try{
            $f_inicio = Carbon::createFromFormat('Y-m-d', $request['fecha_inicio']);
            $f_fin = Carbon::createFromFormat('Y-m-d', $request['fecha_finalizacion']);

          
            $tarea =  Tarea::where('id', '=',$id)
            ->update([
                'fecha_inicio' => $f_inicio,
                'fecha_finalizacion' => $f_fin,
            ]);
            
            
                       
        }catch(\Exception $e){
            return \Response::json(
                ['status' => $e->getMessage()], 422
            );
        }
        return $tarea;
    }

   
 
    public function store(CrearTareaRequest $request)
    {   
        try{
            $user  = Auth::id();
            $f_inicio = Carbon::createFromFormat('Y-m-d', $request['fecha_inicio']);
            $f_fin = Carbon::createFromFormat('Y-m-d', $request['fecha_finalizacion']);
            

            $tarea = Tarea::create([
                'nombre_tarea' 			=> $request['nombre_tarea'],
                'fecha_inicio' 		    => $f_inicio,
                'fecha_finalizacion' 	=> $f_fin,
                'usuario'               => $user,
                ]);


            if ($request->hasFile('archivo')) 
            {
                //obtenemos el archivo
                $archivo = $request->file('archivo');
                //obtenemos el nombre del archivo
                $nombre =  uniqid().'.'. $archivo->getClientOriginalName();
                //se mueve o almacena el archivo al servidor
                
                $patch = 'documentos/tareas/'.$user.'';

                $request->file('archivo')->move($patch, $nombre);
                //$request->file('archivo')->move(public_path("documentos"),$nombre);
                //Storage::disk('public')->put($nombre, $archivo);

                Archivo::create([
                    'tarea' =>  $tarea->id, 
                    'ruta'  =>  $patch.'/'.$nombre,
                    'nombre_archivo' => $archivo->getClientOriginalName() ,
                    'tipo' => $archivo->getClientOriginalExtension(),
                ]);
                            

            }



            
        
        }catch(\Exception $e){
                return \Response::json(
                    ['status' => $e->getMessage()], 422
                );
            }
        return $tarea;

        
    }

 
    public function destroy($id)
    {
        try{
            $user  =  Auth::user();
            $tarea = $user->tareas()->where('id','=',$id)->get();
            

            if($tarea->first() != null){
                $log_info =  'id: '.$tarea[0]->id.' Nombre: '.$tarea[0]->nombre_tarea.' estado: '.$tarea[0]->estado;

                Evento::create([
                    'usuario' 			=> $user->id,
                    'registro' 		    => $log_info,
                    'accion'         	=> 'Eliminar Tarea',
                    ]);
    
    
                $tarea[0]->delete();
            }

           
        }catch(\Exception $e){
            return \Response::json(
                ['status' => $e->getMessage()], 422
            );
        }
        return $tarea;
    }
}
