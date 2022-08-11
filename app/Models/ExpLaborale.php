<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExpLaborale extends Model
{
    use HasFactory;

    protected $fillable = [
        'usuario_id',
        'puesto',
        'institucion',
        'fecha_ini',
        'fecha_fin',
        'ref_laboral',
        'tel_ref_lab',
        'tel_ref_lab2',
        'constancia',
        'tipo_doc_id',
        'eval_desemp',
        'salario',
        'user_crea',
        'fecha_crea',
        'user_mod',
        'fecha_mod'
    ];

    public $timestamps = false;

    public function tipoDoc()
    {
        return $this->belongsTo(Tipodoc::class);
    }

    public function usuarios()
    {
        return $this->belongsTo(Usuario::class);
    }
}
