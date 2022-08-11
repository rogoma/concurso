<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

use Illuminate\Support\Facades\Session;

class ValidacionExpLaborales extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return isAuth();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'usuario_id' => 'required|integer',
            'puesto' => 'required|max:100',
            'institucion' => 'required|max:100',
            'fecha_ini' => 'required|date_format:Y-m-d',
            'fecha_fin' => 'nullable|date_format:Y-m-d',
            'ref_laboral' => 'required|max:100',
            'tel_ref_lab' => 'required|max:20',
            'tel_ref_lab2' => 'nullable|max:20',
            'constancia' => 'required|file|max:5120|mimetypes:application/pdf,application/octet-stream',
            'tipo_doc_id' => 'required|integer',
            'user_crea' => 'required|max:20',
        ];
    }

    public function messages()
    {
        return [
            'constancia.max' => "Tamañao máximo a cargar es 5MB (5120 KB). Si su :attribute es mayor, debe reducir el mismo"
        ];
    }
}
