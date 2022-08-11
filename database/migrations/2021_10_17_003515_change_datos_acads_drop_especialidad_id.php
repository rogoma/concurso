<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeDatosAcadsDropEspecialidadId extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('datos_acads', function (Blueprint $table) {
            $table->dropForeign('fk_especialidad_id_cargo');
            $table->dropColumn('especialidad_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('datos_acads', function (Blueprint $table) {
            $table->unsignedInteger('especialidad_id')->nullable();
            $table->foreign('especialidad_id', 'fk_especialidad_id_cargo')->references('id')->on('cargos')->onDelete('restrict')->onUpdate('restrict');

        });
    }
}
