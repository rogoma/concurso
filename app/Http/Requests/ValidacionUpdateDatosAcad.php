<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

use Illuminate\Support\Facades\Session;

class ValidacionUpdateDatosAcad extends FormRequest
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
        //dd($this->all());
        return [
            'usuario_id' => 'required|integer:datos_acads,usuario_id,'.$this->route('id'),
            'tipo_doc_id' => 'required|integer:datos_acads,tipo_doc_id,'.$this->route('id'),
            'titulo' => 'required|max:50,'.$this->route('id'),
            'especialidad_id' => 'nullable|integer:datos_acads,especialidad_id,'.$this->route('id'),
            'fecha_graduac' => 'required|date_format:Y-m-d',
            'institucion' => 'required|max:50:datos_acads,institucion,'.$this->route('id'),
            'profesion' => 'nullable|max:100:datos_acads,profesion,'.$this->route('id'),
            'pdf' => 'nullable|file|max:5120|mimetypes:application/pdf,application/octet-stream',
            'dato_adic' => 'nullable|string:datos_acads,dato_adic,'.$this->route('id'),
            'user_mod' => 'required|max:20,'.$this->route('id'),
        ];
    }

    public function messages()
    {
        return [
            'pdf.max' => "Tamañao máximo a cargar es 5MB (5120 KB). Si su :attribute es mayor, debe reducir el mismo"
        ];
    }
}
