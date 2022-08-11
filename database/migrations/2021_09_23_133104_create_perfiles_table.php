<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePerfilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $tableName = "perfiles";
        Schema::create($tableName, function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('usuario_id')->unique();
            $table->foreign('usuario_id', 'fk_usuario_id_perfil')->references('id')->on('usuarios')->onDelete('cascade')->onUpdate('cascade');
            $table->string('apellidos', 60)->nullable();
            $table->string('nombres', 60)->nullable();
            $table->string('foto', 100)->nullable();
            $table->string('genero', 1)->nullable();
            $table->string('direccion', 100)->nullable();
            $table->string('dpto', 60)->nullable();
            $table->string('ciudad', 60)->nullable();
            $table->string('telef_cel', 20)->nullable();
            $table->string('telef_2', 20)->nullable();
            $table->date('fecha_nac')->nullable();
            $table->string('postulante', 1)->nullable();
            $table->string('user_crea', 20);
            $table->timestamp('fecha_crea');
            $table->string('user_mod', 20)->nullable();
            $table->dateTime('fecha_mod')->nullable();
        });

        DB::statement("COMMENT ON TABLE {$tableName} IS 'Tabla de Perfiles de Usuarios. genero = M/F; postulante = I/E (interno o Externo)'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('perfiles');
    }
}
