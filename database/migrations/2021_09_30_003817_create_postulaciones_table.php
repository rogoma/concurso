<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePostulacionesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('postulaciones', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('concurso_id');
            $table->foreign('concurso_id', 'fk_concurso_id')->references('id')->on('concursos')->onDelete('cascade')->onUpdate('cascade');
            $table->unsignedBigInteger('usuario_id');
            $table->foreign('usuario_id', 'fk_usuario_id')->references('id')->on('usuarios')->onDelete('cascade')->onUpdate('cascade');
            $table->date('fecha_post');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('postulaciones');
    }
}
