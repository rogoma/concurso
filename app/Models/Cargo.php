<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cargo extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'descripcion',
        'user_crea',
        'fecha_crea',
        'user_mod',
        'fecha_mod'
    ];

    public function academicos()
    {
        return $this->hasMany(DatosAcad::class, 'id', 'especialidad_id');
    }

    public function concursos()
    {
        return $this->hasMany(Concurso::class, 'id', 'cargo_id');
    }
}
