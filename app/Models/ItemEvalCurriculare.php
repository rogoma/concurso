<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItemEvalCurriculare extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    //protected $table = 'item_eval_curriculares';

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
        'item',
        'tipo',
        'campo_nro',
        'user_crea',
        'fecha_crea',
        'user_mod',
        'fecha_mod',
    ];

    public function matrizcurric()
    {
        return $this->hasOne(MatrizCurriculare::class);
    }

    public function evalcurr()
    {
        return $this->hasMany(EvalCurriculare::class);
    }
}
