<?php

namespace App\Actions\Fortify;

use App\Models\Usuario;
use App\Models\Role;
use App\Models\Perfile;
use App\Models\Postulante;
use App\Models\Configuracione;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\URL;
use Illuminate\Validation\Rule;
use Laravel\Fortify\Contracts\CreatesNewUsers;


class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules;

    /**
     * Validate and create a newly registered user.
     *
     * @param  array  $input
     * @return \App\Models\Usuario
     */
    public function create(array $input)
    {
        /*
        Verificación Inicial de Registro
        0- Validar los datos
        1- Verificar si la CI está en Postulante
        2- De estár continuar con el registro
        2.1- Crear el storage
        3- De no estár verificar si esta habilitado el registro externo
        4- De estarlo continuar el registro
        4.1- Crear el storage
        5- De no estarlo cancelar el registro
         */
        Validator::make($input, [
            'ci' => ['required', 'alpha_num', 'max:12'],
            'email' => [
                'required',
                'string',
                'email:rfc,dns',
                'max:60',
                Rule::unique(Usuario::class),
            ],
            'password' => $this->passwordRules(),
        ])->validate();

        $postulante = $this->chekPostulante($input['ci']);
        if ($postulante !== false) {
            if(!$usuario = $this->createUser($input)) {
                return false;
                //redirect()->route('register')->with('erros', 'Usuario no Creado');
            } else {
                $usuario->rol()->attach(Role::where('slug', 'postulantes')->first());
                $this->createProfile($postulante, $usuario);
                $this->createStorage($usuario->ci);
                return $usuario;
            }
        } else {
            $conf = Configuracione::find(1);
            if ($conf->habilitacion_ext === true) {
                if(!$usuario = $this->createUser($input)) {
                    return false;
                    //redirect()->route('register')->with('erros', 'Usuario no Creado');
                } else {
                    $usuario->rol()->attach(Role::where('slug', 'postulantes')->first());
                    $this->createProfile($postulante, $usuario);
                    $this->createStorage($usuario->ci);
                    return $usuario;
                }
            } else {
                if(!$usuario = $this->createUser($input)) {
                    return false;
                    //redirect()->route('register')->with('erros', 'Usuario no Creado');
                } else {
                    $usuario->rol()->attach(Role::where('slug', 'postulantes')->first());
                    $this->createProfile(null, $usuario);
                    $this->createStorage($usuario->ci);
                    return $usuario;
                }
                //return Redirect::to(URL::previous())->withInput()->with('error', 'Registro Externo no habilitado');
                //return redirect()->route('/register')->with('erros', 'Registro Externo no habilitado');
            }
        }
    }

    private function chekPostulante($ci) {
        $postulante = (object)Postulante::where('ci', $ci)->get();
        if (count($postulante) > 0) {
            return $postulante[0];
        } else {
            return false;
        }
    }

    private function createUser(array $datos) {
        return Usuario::create([
                    'ci' => $datos['ci'],
                    'email' => $datos['email'],
                    'password' => Hash::make($datos['password']),
                    'activo' => false,
                    'user_crea'  => $datos['ci'],
                    'fecha_crea' => date('Y-m-d H:i:s'),
                ]);
    }

    private function createProfile($postulante, $usuario) {
        if ($postulante) {
            Perfile::create([
                'apellidos'  => $postulante->apellidos,
                'nombres'    => $postulante->nombres,
                'genero'     => $postulante->genero,
                'direccion'  => $postulante->direccion,
                'dpto'       => $postulante->dpto,
                'ciudad'     => $postulante->ciudad,
                'telef_cel'  => $postulante->telef_cel,
                'telef_2'    => $postulante->telef_2,
                'fecha_nac'  => $postulante->fecha_nac,
                'postulante' => 'I',
                'usuario_id' => $usuario->id,
                'user_crea'  => $usuario->ci,
                'fecha_crea' => date('Y-m-d'),
            ]);
        } else {
            Perfile::create([
                'usuario_id' => $usuario->id,
                'postulante' => 'E',
                'user_crea'  => $usuario->ci,
                'fecha_crea' => date('Y-m-d'),
            ]);
        }
    }

    private function createStorage($ci) {
        Storage::makeDirectory('documentos/'.$ci);
        Storage::makeDirectory('documentos/'.$ci.'/academico');
        Storage::makeDirectory('documentos/'.$ci.'/capacitacion');
        Storage::makeDirectory('documentos/'.$ci.'/laboral');
        Storage::makeDirectory('documentos/'.$ci.'/perfil');
        Storage::makeDirectory('documentos/'.$ci.'/personal');
    }

}
