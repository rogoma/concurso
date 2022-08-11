<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePostulantesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('postulantes', function (Blueprint $table) {
            $table->string('ci', 12)->primary()->unique();
            $table->string('apellidos', 60)->nulleable();
            $table->string('nombres', 60)->nullable();
            $table->string('foto', 100)->nullable();
            $table->string('genero', 1)->nullable();
            $table->string('direccion', 100)->nullable();
            $table->string('dpto', 60)->nullable();
            $table->string('ciudad', 60)->nullable();
            $table->string('telef_cel', 20)->nullable();
            $table->integer('telef_2')->nullable();
            $table->string('email', 60)->nullable();
            $table->date('fecha_nac')->nullable();
            $table->string('institucion', 100)->nullable();
            $table->string('antiguedad', 60)->nullable();
            $table->text('eval_desemp')->nullable();
            $table->biginteger('salario')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('postulantes');
    }
}
