<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TipoRechazoDoc extends Model
{
    use HasFactory;

    protected $table = 'tipo_rechazo_docs';

    //protected $guarded = [];

    protected $fillable = [
        'tipo_doc_id',
        'rechazo',
        'user_crea',
        'user_mod'
    ];

    //public $primaryKey = 'id';

    public $timestamps = false;

    public function tipoDoc()
    {
        return $this->belongsTo(Tipodoc::class);
    }

    public function evaldocumental()
    {
        return $this->hasMany(EvalDocumentale::class);
    }
}
