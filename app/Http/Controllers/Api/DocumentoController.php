<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Documento;

class DocumentoController extends Controller
{
    
    public function index()
    {
        $documentos  =  Documento::all();
        return \Response::json($documentos,200);
    }

  
}
