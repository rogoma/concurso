<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCapacitacionesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('capacitaciones', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('usuario_id');
            $table->foreign('usuario_id', 'fk_idusuario_capacitacion')->references('id')->on('usuarios')->onDelete('restrict')->onUpdate('restrict');
            $table->string('tema_curso', 150);
            $table->string('carga_horaria', 150);
            $table->date('fecha_ini');
            $table->date('fecha_fin');
            $table->string('institucion', 100);
            $table->string('constancia', 100);
            $table->string('user_crea', 20);
            $table->timestamp('fecha_crea');
            $table->string('user_mod', 20)->nullable();
            $table->dateTime('fecha_mod')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('capacitaciones');
    }
}
