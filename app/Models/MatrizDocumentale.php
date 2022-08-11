<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MatrizDocumentale extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    //protected $table = 'matriz_documentales';

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'tipo_doc_id',
        'concurso_id',
        'requerido',
        'user_crea',
        'fecha_crea',
        'user_mod',
        'fecha_mod',
    ];

    public function concurso()
    {
        return $this->belongsTo(Concurso::class);
    }

    public function tipoDoc()
    {
        return $this->belongsTo(Tipodoc::class);
    }
}
