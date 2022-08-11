<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateConcursosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('concursos', function (Blueprint $table) {
            $table->id();
            $table->string('decripcion', 50);
            $table->unsignedBigInteger('cargo_id')->nullable();
            //$table->foreign('cargo_id', 'fk_cargo_id')->references('id')->on('cargos')->onDelete('set null')->onUpdate('cascade');
            $table->integer('cantidad_vacanc');
            $table->integer('salario');
            $table->date('ini_post');
            $table->date('fin_post');
            $table->date('ini_eva_doc');
            $table->date('fin_eva_doc');
            $table->date('ini_eva_cur');
            $table->date('fin_eva_cur');
            $table->date('ini_examen');
            $table->date('fin_examen');
            $table->date('ini_entrevista');
            $table->date('fin_entrevista');
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
        Schema::dropIfExists('concursos');
    }
}
