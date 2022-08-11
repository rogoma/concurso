<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExpLaboralesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $tableName = "exp_laborales";
        Schema::create($tableName, function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('usuario_id');
            $table->foreign('usuario_id', 'fk_idusuario_exp_lab')->references('id')->on('usuarios')->onDelete('restrict')->onUpdate('restrict');
            $table->string('puesto', 100);
            $table->string('institucion', 100);
            $table->date('fecha_ini');
            $table->date('fecha_fin');
            $table->string('ref_laboral', 100);
            $table->string('tel_ref_lab', 20);
            $table->string('tel_ref_lab2', 20);
            $table->string('constancia', 100)->nullable();
            $table->string('user_crea', 20);
            $table->timestamp('fecha_crea');
            $table->string('user_mod', 20)->nullable();
            $table->dateTime('fecha_mod')->nullable();
        });

        DB::statement("COMMENT ON TABLE {$tableName} IS 'Tabla de Experiencia Laboral'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('exp_laborales');
    }
}
