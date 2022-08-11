<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

use Illuminate\Support\Facades\Session;

class ValidacionDatosAcad extends FormRequest
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
            'tipo_doc_id' => 'required|integer',
            'titulo' => 'required|max:50',
            'pdf' => 'required|file|max:5120|mimetypes:application/pdf,application/octet-stream',
            'fecha_graduac' => 'required|date_format:Y-m-d',
            'institucion' => 'required|max:50',
            'profesion' => 'max:100|nullable',
            'dato_adic' => 'string|nullable',
            'user_crea' => 'required|max:20',
        ];
    }

    public function messages()
    {
        return [
            'pdf.max' => "Tamañao máximo a cargar es 5MB (5120 KB). Si su :attribute es mayor, debe reducir el mismo"
        ];
    }
}
