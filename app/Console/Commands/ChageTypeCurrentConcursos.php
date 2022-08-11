<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ChageTypeCurrentConcursos extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'concursos:altertable';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Modificador de Campos de Fecha del Sistema de Concursos';

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
        $this->alterDB();
        $this->line('Tablas Modificadas.');
    }

    private function alterDB()
    {
        DB::statement('
            ALTER TABLE IF EXISTS public.capacitaciones
                ALTER COLUMN fecha_crea SET DEFAULT CURRENT_TIMESTAMP;');
        DB::statement('
            ALTER TABLE IF EXISTS public.capacitaciones
                ALTER COLUMN fecha_mod SET DEFAULT CURRENT_TIMESTAMP;');

        DB::statement('
            ALTER TABLE IF EXISTS public.cargos
                ALTER COLUMN fecha_crea SET DEFAULT CURRENT_TIMESTAMP;');
        DB::statement('
            ALTER TABLE IF EXISTS public.cargos
                ALTER COLUMN fecha_mod SET DEFAULT CURRENT_TIMESTAMP;');

        DB::statement('
            ALTER TABLE IF EXISTS public.concursos
                ALTER COLUMN fecha_crea SET DEFAULT CURRENT_TIMESTAMP;');
        DB::statement('
            ALTER TABLE IF EXISTS public.concursos
                ALTER COLUMN fecha_mod SET DEFAULT CURRENT_TIMESTAMP;');

        DB::statement('
            ALTER TABLE IF EXISTS public.configuraciones
                ALTER COLUMN fecha_crea SET DEFAULT CURRENT_TIMESTAMP;');
        DB::statement('
            ALTER TABLE IF EXISTS public.configuraciones
                ALTER COLUMN fecha_mod SET DEFAULT CURRENT_TIMESTAMP;');

        DB::statement('
            ALTER TABLE IF EXISTS public.datos_acads
                ALTER COLUMN fecha_crea SET DEFAULT CURRENT_TIMESTAMP;');
        DB::statement('
            ALTER TABLE IF EXISTS public.datos_acads
                ALTER COLUMN fecha_mod SET DEFAULT CURRENT_TIMESTAMP;');

        DB::statement('
            ALTER TABLE IF EXISTS public.datos_personales
                ALTER COLUMN fecha_crea SET DEFAULT CURRENT_TIMESTAMP;');
        DB::statement('
            ALTER TABLE IF EXISTS public.datos_personales
                ALTER COLUMN fecha_mod SET DEFAULT CURRENT_TIMESTAMP;');

        DB::statement('
            ALTER TABLE IF EXISTS public.exp_laborales
                ALTER COLUMN fecha_crea SET DEFAULT CURRENT_TIMESTAMP;');
        DB::statement('
            ALTER TABLE IF EXISTS public.exp_laborales
                ALTER COLUMN fecha_mod SET DEFAULT CURRENT_TIMESTAMP;');

        DB::statement('
            ALTER TABLE IF EXISTS public.perfiles
                ALTER COLUMN fecha_crea SET DEFAULT CURRENT_TIMESTAMP;');
        DB::statement('
            ALTER TABLE IF EXISTS public.perfiles
                ALTER COLUMN fecha_mod SET DEFAULT CURRENT_TIMESTAMP;');

        DB::statement('
            ALTER TABLE IF EXISTS public.roles
                ALTER COLUMN fecha_crea SET DEFAULT CURRENT_TIMESTAMP;');
        DB::statement('
            ALTER TABLE IF EXISTS public.roles
                ALTER COLUMN fecha_mod SET DEFAULT CURRENT_TIMESTAMP;');

        DB::statement('
            ALTER TABLE IF EXISTS public.tipo_docs
                ALTER COLUMN fecha_crea SET DEFAULT CURRENT_TIMESTAMP;');
        DB::statement('
            ALTER TABLE IF EXISTS public.tipo_docs
                ALTER COLUMN fecha_mod SET DEFAULT CURRENT_TIMESTAMP;');

        DB::statement('
            ALTER TABLE IF EXISTS public.tipo_rechazo_docs
                ALTER COLUMN fecha_crea SET DEFAULT CURRENT_TIMESTAMP;');
        DB::statement('
            ALTER TABLE IF EXISTS public.tipo_rechazo_docs
                ALTER COLUMN fecha_mod SET DEFAULT CURRENT_TIMESTAMP;');

        DB::statement('
            ALTER TABLE IF EXISTS public.usuarios
                ALTER COLUMN fecha_crea SET DEFAULT CURRENT_TIMESTAMP;');
        DB::statement('
            ALTER TABLE IF EXISTS public.usuarios
                ALTER COLUMN fecha_mod SET DEFAULT CURRENT_TIMESTAMP;');

        DB::statement('
            ALTER TABLE IF EXISTS public.concursos
                ADD CONSTRAINT fk_cargo_id FOREIGN KEY (cargo_id)
                REFERENCES public.cargos (id) MATCH SIMPLE
                ON UPDATE CASCADE
                ON DELETE SET NULL
                NOT VALID;');
    }

}
