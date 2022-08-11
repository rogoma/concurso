<?php

namespace App\Http\Requests;

use App\Traits\Concursos;
use Illuminate\Support\Facades\Session;
use Illuminate\Foundation\Http\FormRequest;

class ValidacionConcursos extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return isAdmin();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        /*if ($this->fecha_ini) {
            $this->fecha_ini = $this->fixFecha($this->fecha_ini);
        }
        if ($this->fecha_fin) {
            $this->fecha_fin = $this->fixFecha($this->fecha_fin);
        }*/
        return [
            'decripcion' => 'required|string|max:50',
            'cargo_id' => 'required|integer',
            'cantidad_vacanc' => 'required|integer',
            'salario' => 'required|integer',
            'ini_post' => 'required|date_format:Y-m-d',
            'fin_post' => 'required|date_format:Y-m-d',
            'ini_eva_doc' => 'required|date_format:Y-m-d',
            'fin_eva_doc' => 'required|date_format:Y-m-d',
            'ini_eva_cur' => 'required|date_format:Y-m-d',
            'fin_eva_cur' => 'required|date_format:Y-m-d',
            'ini_examen' => 'required|date_format:Y-m-d',
            'fin_examen' => 'required|date_format:Y-m-d',
            'ini_entrevista' => 'required|date_format:Y-m-d',
            'fin_entrevista' => 'required|date_format:Y-m-d',
            'perfil' => 'required|string|mas:300',
            'proceso' => 'required|string|mas:300',
            'user_crea' => 'required|string|max:20',
            'fecha_crea' => 'required|date_format:Y-m-d',
        ];
    }
}
