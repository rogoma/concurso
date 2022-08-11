<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Examene extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    //protected $table = 'examenes';

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
        'concurso_id',
        'hora_ini',
        'hora_fin',
        'puntaje',
        'preguntas',
        'cant_preg_examen',
        'confirmado',
        'user_crea',
        'fecha_crea',
        'user_mod',
        'fecha_mod',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'preguntas' => 'array',
    ];

    public function concurso()
    {
        return $this->belongsTo(Concurso::class);
    }
}
