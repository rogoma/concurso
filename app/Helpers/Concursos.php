<?php

//use App\Models\Permiso;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Session;

if (!function_exists('isAdmin')) {
    function isAdmin()
    {
        return Session::get('role_slug') == 'administrador';
    }
}

if (!function_exists('isValidador')) {
    function isValidador()
    {
        if(Session::get('role_slug') == 'validador')
            return true;
        return false;
    }
}

if (!function_exists('isAuth')) {
    function isAuth()
    {
        if(Session::get('role_slug') == 'postulantes')
            return true;
        return false;
    }
}

/*
if (!function_exists('canUser')) {
    function canUser($permiso, $redirect = true)
    {
        if (Session::get('role_slug') == 'administrador') {
            return true;
        } else {
            $role_id = session('role_id');
            $permisos = Cache::tags('Permisos')->rememberForever("Permisos.rolid.$role_id", function () use($role_id) {
                return Permiso::whereHas('roles', function (Builder $query) use($role_id) {
                    $query->where('role_id', session()->get('role_id'));
                })->get()->pluck('slug')->toArray();
            });
            if (!in_array($permiso, $permisos)) {
                if ($redirect) {
                    abort(403, 'No tienes permiso de acceso');
                } else {
                    return false;
                }
            }
            return true;
        }
    }
}*/
