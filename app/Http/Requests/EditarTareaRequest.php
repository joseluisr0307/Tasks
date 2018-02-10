<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EditarTareaRequest extends FormRequest
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
            'id' 	                    =>	'required|numeric|min:1|exists:tareas',
            'fecha_inicio' 	            =>	'required|date',
			'fecha_finalizacion'		=>	'required|date',			
			'archivo'					=>	'nullable|mimes:pdf,jpeg,gif,png',
        ];
    }
}
