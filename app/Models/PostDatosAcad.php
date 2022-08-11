<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PostDatosAcad extends Model
{
    use HasFactory;

    protected $table = 'post_datos_acads';

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $guarded = [];

    public $timestamps = false;

    public function tipoDoc()
    {
        return $this->belongsTo(Tipodoc::class);
    }
}
