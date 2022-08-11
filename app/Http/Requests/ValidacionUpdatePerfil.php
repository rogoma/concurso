<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

use Illuminate\Support\Facades\Session;

class ValidacionUpdatePerfil extends FormRequest
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
            'nombres' => 'max:60:perfiles,nombres,'.$this->route('id'),
            'apellidos' => 'max:60:perfiles,apellidos,'.$this->route('id'),
            'foto' => 'image|mimes:png,jpg|max:2048|nullable:perfiles,foto,'.$this->route('id'),
            'genero' => 'max:1|nullable:perfiles,genero,'.$this->route('id'),
            'direccion' => 'max:100|nullable:perfiles,direccion,'.$this->route('id'),
            'dpto' => 'max:60|nullable:perfiles,dpto,'.$this->route('id'),
            'ciudad' => 'max:60|nullable:perfiles,ciudad,'.$this->route('id'),
            'telef_cel' => 'max:20|nullable:perfiles,telef_cel,'.$this->route('id'),
            'telef_2' => 'max:20|nullable:perfiles,telef_2,'.$this->route('id'),
            'fecha_nac' => 'date_format:Y-m-d|nullable:perfiles,fecha_nac,'.$this->route('id'),
            'postulante' => 'max:1|nullable:perfiles,postulante,'.$this->route('id'),
            'user_mod' => 'nullable|max:20:perfiles,user_mod,'.$this->route('id'),
        ];
    }

    public function messages()
    {
        return [
            'constancia.max' => "Tamañao máximo a cargar es 5MB (5120 KB). Si su :attribute es mayor, debe reducir el mismo"
        ];
    }
}
