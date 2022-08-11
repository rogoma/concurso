<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DatosAcad extends Model
{
    use HasFactory;

    protected $fillable = [
        'usuario_id',
        'tipo_doc_id',
        'titulo',
        'especialidad_id',
        'pdf',
        'fecha_graduac',
        'institucion',
        'profesion',
        'dato_adic',
        'user_crea',
        'fecha_crea',
        'user_mod',
        'fecha_mod'
    ];

    public $timestamps = false;

    public function usuarios()
    {
        return $this->belongsTo(Usuario::class);
    }

    public function tipoDoc()
    {
        return $this->belongsTo(Tipodoc::class);
    }

    public function cargo()
    {
        return $this->belongsTo(Cargo::class, 'especialidad_id');
    }

}
