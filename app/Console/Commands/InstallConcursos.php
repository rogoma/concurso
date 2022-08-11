<?php

namespace App\Console\Commands;

use App\Models\Role;
use App\Models\Usuario;
use App\Models\Perfile;
use App\Models\RoleUsuario;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class InstallConcursos extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'concursos:install';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Instalador del Sistema de Concursos';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        if (!$this->verifiy()) {
            if (!$rol = $this->crearRol()) {
                $this->error('Instalador abordado. Rol No Creado');
            } else {
                if (!$user = $this->crearAdmin()) {
                    $this->error('Instalador abordado. Usuario No Creado');
                } else {
                    $user->rol()->attach(Role::where('slug', 'administrador')->first());
                    if (!$perfil = $this->crearPerfilAdmin()) {
                        $this->error('Instalador abordado. Perfil No Creado');
                    } else {
                        if ($this->chekRelation() < 1) {
                            $this->error('Instalador abordado. Relacion No Creada');
                        } else {
                            Storage::makeDirectory('documentos');
                            $this->line('Rol y Usuario creados');
                        }
                    }
                }
            }
        } else {
            $this->error('No se puede ejecutar el Instalador. Rol existente');
        }
    }

    private function verifiy() {
        return Role::find(1);
    }

    private function crearRol() {
        $roles = array(
            array('rol' => 'ADMINISTRADOR',
                    'slug' => Str::slug('ADMINISTRADOR', '_'),
                    'user_crea' => 'admin',
                    'fecha_crea' => '2021-09-14'),
            array('rol' => 'VALIDADOR',
                    'slug' => Str::slug('VALIDADOR', '_'),
                    'user_crea' => 'admin',
                    'fecha_crea' => '2021-09-14'),
            array('rol' => 'POSTULANTES',
                    'slug' => Str::slug('POSTULANTES', '_'),
                    'user_crea' => 'admin',
                    'fecha_crea' => '2021-09-14')
        );
        foreach ($roles as $r) {
            Role::create($r);
        }
        return true;
    }

    private function crearAdmin() {
        return Usuario::create([
            'ci'         => 'admin',
            'email'      => 'admin@concursos.py',
            'password'   => Hash::make('Admin321.'),
            'user_crea'  => 'admin',
            'fecha_crea' => '2021-09-14',
            'activo'     => true,
            'email_verified_at' => date('Y-m-d H:i:s')
        ]);
    }

    private function crearPerfilAdmin() {
        return Perfile::create([
            'usuario_id'  => '1',
            'nombres'    => 'Administrador',
            'apellidos'  => 'De Concursos',
            'genero'     => 'M',
            'direccion'  => 'PETIROSSI C/BRASIL',
            'dpto'       => 'CAPITAL',
            'ciudad'     => 'ASUNCIÓN',
            'telef_cel'  => '0999-999000',
            'telef_2'    => '021-999555',
            'fecha_nac'  => date('Y-m-d'),
            'postulante' => 0,
            'user_crea'  => 'admin',
            'fecha_crea' => '2021-09-14'
        ]);
    }

    private function chekRelation() {
        $relation = RoleUsuario::where('usuario_id', '=', 1)
                        ->where('role_id', '=', 1)
                        ->get();
        return $relation->count();
    }

    /**
    * @return int
    */
    protected function putSequenceID($seq, $id)
    {
        $next_id = DB::connection('pgsql')->select("SELECT setval('$seq', $id, true)");
        return intval($next_id['0']->setval);
    }
}
