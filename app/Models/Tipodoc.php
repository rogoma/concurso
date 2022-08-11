<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tipodoc extends Model
{
    use HasFactory;

    protected $table = 'tipo_docs';

    protected $guarded = [];

    //public $primaryKey = 'id';

    public $timestamps = false;


    public function rechazos()
    {
        return $this->hasMany(TipoRechazoDoc::class);
    }

    public function personales()
    {
        return $this->hasMany(DatosPersonale::class);
    }

    public function portPersonales()
    {
        return $this->hasMany(PostDatosPersonale::class);
    }

    public function academicos()
    {
        return $this->hasMany(DatosAcad::class);
    }

    public function postAcademicos()
    {
        return $this->hasMany(PostDatosAcad::class);
    }

    public function capaciones()
    {
        return $this->hasMany(Capacitacione::class);
    }

    public function postCapaciones()
    {
        return $this->hasMany(PostDatosCapacitacion::class);
    }

    public function laborales()
    {
        return $this->hasMany(ExpLaborale::class);
    }

    public function postLaborales()
    {
        return $this->hasMany(PostExpLaborale::class);
    }

    public function matriz()
    {
        return $this->hasMany(MatrizDocumentale::class);
    }

    public function evaldocumental()
    {
        return $this->hasMany(EvalDocumentale::class);
    }

}
