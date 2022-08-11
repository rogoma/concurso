<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Capacitacione extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'usuario_id',
        'tema_curso',
        'carga_horaria',
        'fecha_ini',
        'fecha_fin',
        'institucion',
        'constancia',
        'tipo_doc_id',
        'user_crea',
        'fecha_crea',
        'user_mod',
        'fecha_mod'
    ];

    public function tipoDoc()
    {
        return $this->belongsTo(Tipodoc::class);
    }

    public function usuarios()
    {
        return $this->belongsTo(Usuario::class);
    }
}
