<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Notifications\ResetPasswordNotifcation;
use App\Notifications\VerifyEmailNotifcation;
use Illuminate\Auth\Notifications\VerifyEmail;

class Usuario extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $guarded = [];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public $timestamps = false;

    public function rol()
    {
        return $this->belongsToMany(Role::class);
    }

    public function perfil()
    {
        return $this->hasOne(Perfile::class);
    }

    public function datospersonales()
    {
        return $this->hasMany(DatosPersonale::class);
    }

    public function datosacademicos()
    {
        return $this->hasMany(DatosAcad::class);
    }

    public function capacitaciones()
    {
        return $this->hasMany(Capacitacione::class);
    }

    public function laborales()
    {
        return $this->hasMany(ExpLaborale::class);
    }

    public function postulaciones()
    {
        return $this->hasMany(Postulacione::class);
    }

    /**
     * Send the password reset notification.
     *
     * @param string $token
     * @return void
     */
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ResetPasswordNotifcation($token));
    }

    /**
     * Send the email verification notification.
     *
     * @return void
     */
    public function sendEmailVerificationNotification()
    {
        $this->notify(new VerifyEmailNotifcation);
    }
}
