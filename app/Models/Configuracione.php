<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Configuracione extends Model
{
    use HasFactory;

    protected $fillable = [
        'razon_social',
        'ruc',
        'telefono',
        'email',
        'direccion',
        'dpto',
        'ciudad',
        'habilitacion_ext',
        'user_crea',
        'fecha_crea',
        'user_mod',
        'fecha_mod'
    ];

    public $timestamps = false;
}
