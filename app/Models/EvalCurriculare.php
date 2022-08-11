<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EvalCurriculare extends Model
{
    use HasFactory;

    protected $fillable = [
        'postulacion_id',
        'item_eval_curric_id',
        'puntaje',
        'obs'
    ];

    public $timestamps = false;

    public function postulacion()
    {
        return $this->belongsTo(Postulacione::class);
    }

    public function itemevalcurric()
    {
        return $this->belongsTo(ItemEvalCurriculare::class, 'item_eval_curric_id');
    }

}
