<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;

    protected $guarded = [];

    //public $primaryKey = 'id';

    public $timestamps = false;


    public function usuarios()
    {
        return $this->hasMany(Usuario::class);
    }
}
