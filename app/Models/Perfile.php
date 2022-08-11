<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Perfile extends Model
{
    use HasFactory;

    //protected $table = 'perfiles';

    protected $guarded = [];

    public $timestamps = false;

    public function usuarios()
    {
        return $this->belongsTo(Usuario::class);
    }

}
