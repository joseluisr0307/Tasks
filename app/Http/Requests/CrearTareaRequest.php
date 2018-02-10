<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CrearTareaRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'nombre_tarea'				=>	'required|string',
		    'fecha_inicio' 	            =>	'required|date',
			'fecha_finalizacion'		=>	'required|date',			
			'archivo'					=>	'nullable|mimes:pdf,jpeg,gif,png',
        ];
    }
}
