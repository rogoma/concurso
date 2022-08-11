<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Postulacione extends Model
{
    use HasFactory;

    protected $fillable = [
        'concurso_id',
        'usuario_id',
        'fecha_post',
        'eval_docum',
        'eval_curric',
        'examen',
        'entrevista',
        'eval_docum_user',
        'eval_docum_fecha',
        'eval_curric_user',
        'eval_curric_fecha',
        'examen_user',
        'examen_fecha',
        'entrevista_user',
        'entrevista_fecha',
        'act_intento_examen'
    ];

    public $timestamps = false;

    public function concurso()
    {
        return $this->belongsTo(Concurso::class);
    }

    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'usuario_id', 'id');
    }

    public function postDatosAcads()
    {
        return $this->hasMany(PostDatosAcad::class, 'postulacion_id');
    }

    public function postCapacitaciones()
    {
        return $this->hasMany(PostDatosCapacitacion::class, 'postulacion_id');
    }

    public function postDatosPersonales()
    {
        return $this->hasMany(PostDatosPersonale::class, 'postulacion_id');
    }

    public function postExpLaborales()
    {
        return $this->hasMany(PostExpLaborale::class, 'postulacion_id');
    }

    public function evaldocumental()
    {
        return $this->hasMany(EvalDocumentale::class, 'postulacion_id');
    }

    public function evalcurric()
    {
        return $this->hasMany(EvalCurriculare::class, 'postulacion_id');
    }
}
