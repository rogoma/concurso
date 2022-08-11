<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

use Illuminate\Support\Facades\Session;

class ValidacionUpdateDatosPersonales extends FormRequest
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
            'usuario_id' => 'required|integer:datos_personales,usuario_id,'.$this->route('id'),
            'tipo_doc_id' => 'required|integer:datos_personales,tipo_doc_id,'.$this->route('id'),
            'pdf' => 'nullable|max:5120|mimetypes:application/pdf,application/octet-stream',
            'fecha' => 'required|date_format:Y-m-d,'.$this->route('id'),
            'dato_adic' => 'nullable|string',
            'user_mod' => 'required|max:20',
        ];
    }

    public function messages()
    {
        return [
            'pdf.max' => "Tamañao máximo a cargar es 5MB (5120 KB). Si su :attribute es mayor, debe reducir el mismo"
        ];
    }
}
