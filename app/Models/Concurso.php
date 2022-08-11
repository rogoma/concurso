<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Concurso extends Model
{
    use HasFactory;

    //protected $table = 'concursos';

    protected $guarded = [];

    public $timestamps = false;

    public function cargos()
    {
        return $this->belongsTo(Cargo::class, 'cargo_id');
    }

    public function postulaciones()
    {
        return $this->hasMany(Postulacione::class);
    }

    public function matrizdocs()
    {
        return $this->hasMany(MatrizDocumentale::class);
    }

    public function matrizcurric()
    {
        return $this->hasMany(MatrizCurriculare::class);
    }

    public function examenes()
    {
        return $this->hasMany(Examene::class);
    }
}
