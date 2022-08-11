<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EvalExamene extends Model
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
        'postulacion_id',
        'examen_id',
        'hora_examen',
        'puntaje',
        'respuestas',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'respuestas' => 'array',
    ];
}
