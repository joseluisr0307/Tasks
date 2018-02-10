<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Archivo;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ArchivoController extends Controller
{
    
    public function index($id)
    {   
      
        $archivos  =  Archivo::where('tarea', '=',$id)->get()->toArray();
        
        return \Response::json($archivos,200);
    }

    public function store(Request $request)
    {   
      
        try{
            $user  = Auth::id();


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

                $archivo=Archivo::create([
                    'tarea' => $request->get('id'),
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
        return $archivo;

        
    }

    
    public function destroy($id)
    {
        try{
           
            $archivo = Archivo::where('id','=',$id)->get();

            if($archivo->first() != null){
                Storage::disk('public')->delete($archivo[0]->ruta);
                $archivo[0]->delete();
            }
            
           
        }catch(\Exception $e){
            return \Response::json(
                ['status' => $e->getMessage()], 422
            );
        }
        
    }

}
