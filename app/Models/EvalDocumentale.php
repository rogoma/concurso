<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EvalDocumentale extends Model
{
    use HasFactory;

    protected $fillable = [
        'postulacion_id',
        'tipo_doc_id',
        'cumple',
        'motivo_rechazo_id',
        'obs'
    ];

    public $timestamps = false;

    public function postulacion()
    {
        return $this->belongsTo(Postulacione::class);
    }

    public function tipoDoc()
    {
        return $this->belongsTo(Tipodoc::class);
    }

    public function rechazos()
    {
        return $this->belongsTo(TipoRechazoDoc::class);
    }

}
