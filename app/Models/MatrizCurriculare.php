<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MatrizCurriculare extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    //protected $table = 'matriz_curriculares';

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
        'item_eval_curric_id',
        'concurso_id',
        'puntaje_min',
        'puntaje_max',
        'user_crea',
        'fecha_crea',
        'user_mod',
        'fecha_mod',
    ];

    public function concurso()
    {
        return $this->belongsTo(Concurso::class);
    }

    public function itemevalcurric()
    {
        return $this->belongsTo(ItemEvalCurriculare::class, 'item_eval_curric_id');
    }

}
