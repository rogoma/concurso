<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Traits\Concursos;

class ValidacionUpdateCapacitacione extends FormRequest
{
    use Concursos;
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
        if ($this->fecha_ini) {
            $this->fecha_ini = $this->fixFecha($this->fecha_ini);
        }
        if ($this->fecha_fin) {
            $this->fecha_fin = $this->fixFecha($this->fecha_fin);
        }
        if ( $this->hasFile('constancia') && $this->file('constancia')->extension() !== 'pdf') {
            return back()->withErrors('Extención de Archivo debe ser pdf');
        }
        if ( $this->hasFile('constancia') && ($this->file('constancia')->getSize()/1024 > 5120)) {
            return back()->withErrors('Tamañao máximo a cargar es 5MB (5120 KB). Si su :attribute es mayor, debe reducir el mismo');
        }

        //dd($this->file('constancia')->getSize()/1024);
        $rules = [
            'usuario_id' => 'required|integer:capacitaciones,usuario_id,'.$this->route('id'),
            'tema_curso' => 'required|max:150:capacitaciones,tema_curso,'.$this->route('id'),
            'carga_horaria' => 'required|max:150:capacitaciones,carga_horaria,'.$this->route('id'),
            'institucion' => 'required|max:100:capacitaciones,institucion,'.$this->route('id'),
            'fecha_ini' => 'required|date_format:Y-m-d',
            'fecha_fin' => 'required|date_format:Y-m-d',
            'constancia' => 'nullable|max:5120|mimetypes:application/pdf,application/octet-stream',
            'tipo_doc_id' => 'required|integer:capacitaciones,tipo_doc_id,'.$this->route('id'),
            'user_mod' => 'required|max:20,'.$this->route('id'),
        ];
        return $rules;
    }
}
