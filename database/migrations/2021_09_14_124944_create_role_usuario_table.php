<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRoleUsuarioTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('role_usuario', function (Blueprint $table) {
            $table->unsignedBigInteger('usuario_id');
            $table->foreign('usuario_id', 'fk_usuario_id_rol')->references('id')->on('usuarios')->onDelete('cascade')->onUpdate('cascade');
            $table->unsignedBigInteger('role_id');
            $table->foreign('role_id', 'fk_role_id_usuario')->references('id')->on('roles')->onDelete('cascade')->onUpdate('cascade');
            $table->unique(['usuario_id', 'role_id'], 'usuario__id_rol__id_idx');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('role_usuario');
    }
}
