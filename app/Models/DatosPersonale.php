<?php

namespace App\Models;

use Carbon\Carbon;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DatosPersonale extends Model
{
    use HasFactory;

    //protected $guarded = [];
    protected $fillable = [
        'usuario_id',
        'tipo_doc_id',
        'pdf',
        'fecha',
        'institucion',
        'dato_adic',
        'user_crea',
        'fecha_crea',
        'user_mod',
        'fecha_mod'
    ];

    //protected $dateFormat = 'Y-m-d';
    //public $primaryKey = 'id';

    public $timestamps = false;

    public function usuarios()
    {
        return $this->belongsTo(Usuario::class);
    }


    public function tipoDoc()
    {
        return $this->belongsTo(Tipodoc::class);
    }

    /*
    public function scopeField($query, $field)
    {
       return $query->whith(['dataTypes'=>function($q)use($field){
                  $q->where('data_types.field',$field);
              }]);
    }
    */
}
