<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

use Illuminate\Support\Facades\Session;

class ValidacionPerfil extends FormRequest
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
            'nombres' => 'required|max:12|unique:usuarios,ci',
            'apellidos' => 'required|max:60|email|unique:usuarios,email',
            'foto' => 'max:2048|mime:jpg,png|nullable',
            'genero' => 'max:1|nullable',
            'direccion' => 'max:100|nullable',
            'dpto' => 'max:60|nullable',
            'ciudad' => 'max:60|nullable',
            'telef_cel' => 'max:20|nullable',
            'telef_2' => 'max:20|nullable',
            'fecha_nac' => 'date_format:Y-m-d|nullable',
            'postulante' => 'max:1',
            'user_crea' => 'max:20',
        ];
    }
}
