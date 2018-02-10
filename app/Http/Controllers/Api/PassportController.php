<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Support\Facades\Auth;
use Validator;

class PassportController extends Controller
{
    
    public function login(){

        if(Auth::attempt(['email'=> request('email'),'password'=> request('password')])){
            
            $user = Auth::user();
            $success['token'] = $user->createToken('tasks')->accessToken;
            return  \Response::json(['success' => $success],200);
        }else{

            return  \Response::json(['error' => 'Unauthotised'],401);
        }

     
       
    }
    
    public function register(Request $request){

        try{
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:60',
                'last_name' => 'required|string|max:60',
                'type_document' => 'required|numeric|min:1',
                'number_document' => 'required|string|max:60',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => 'required|string|min:6',
                'password_confirm' => 'required|same:password',
                'photo' => 'string',
            ]);

            if($validator->fails()){

                return  \Response::json(['errors' => $validator->errors()],401);
            }


            list($type,$imageData)=explode(';',$archivo = $request->photo);
            list(,$extension)=explode('/',$type);
            list(,$imageData)=explode(',',$imageData);

            if($extension == 'jpeg' || $extension == 'x-png' || $extension == 'gif' || $extension == 'png'){

                $file_name ="documentos/fotografias/". uniqid() . '.' . $extension;

                $source = fopen($request->photo,'r');
                $destination = fopen($file_name,'w');
    
                stream_copy_to_stream($source,$destination);
    
                fclose($source);
                fclose($destination);

                $input = $request->all();
                $input['patch_photo'] = $file_name;

            }else{

                $input = $request->all();

            }

          


            $input['password'] = bcrypt($input['password']);
            
            $user = User::create($input);

            $success['token'] = $user->createToken('tasks')->accessToken;
            $success['name'] = $user->name;

            

        }catch(\Exception $e){
            return \Response::json(
                ['status' => $e->getMessage()], 422
            );
        }

        return  \Response::json(['success' => $success],200);
    }

    public function getDetails(){
        $user = Auth::user();
        return  \Response::json(['success' => $user],200);
    }

}