<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

use Illuminate\Support\Facades\Session;

class ValidacionUpdateExpLaborales extends FormRequest
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
            'usuario_id' => 'required|integer:exp_laborales,usuario_id,'.$this->route('id'),
            'puesto' => 'required|max:100:exp_laborales,puesto,'.$this->route('id'),
            'institucion' => 'required|max:100:exp_laborales,institucion,'.$this->route('id'),
            'constancia' => 'nullable|file|max:5120|mimetypes:application/pdf,application/octet-stream',
            'fecha_ini' => 'required|date_format:Y-m-d',
            'fecha_fin' => 'nullable|date_format:Y-m-d',
            'ref_laboral' => 'required|max:100:exp_laborales,ref_laboral,'.$this->route('id'),
            'tel_ref_lab' => 'required|max:20:exp_laborales,tel_ref_lab,'.$this->route('id'),
            'tel_ref_lab2' => 'nullable|max:20:exp_laborales,tel_ref_lab2,'.$this->route('id'),
            'tipo_doc_id' => 'required|integer:exp_laborales,tipo_doc_id,'.$this->route('id'),
            'user_mod' => 'nullable|max:20:exp_laborales,user_mod,'.$this->route('id'),
        ];
    }

    public function messages()
    {
        return [
            'constancia.max' => "Tamañao máximo a cargar es 5MB (5120 KB). Si su :attribute es mayor, debe reducir el mismo"
        ];
    }
}
