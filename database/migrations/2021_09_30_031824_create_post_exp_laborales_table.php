<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePostExpLaboralesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('post_exp_laborales', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('postulacion_id');
            $table->foreign('postulacion_id', 'fk_postulacion_id')->references('id')->on('postulaciones')->onDelete('cascade')->onUpdate('cascade');
            $table->string('puesto', 100);
            $table->string('institucion', 50);
            $table->date('fecha_ini');
            $table->date('fecha_fin');
            $table->string('ref_laboral', 100);
            $table->string('tel_ref_lab', 20);
            $table->string('tel_ref_lab2', 20);
            $table->string('constancia', 100);
            $table->text('eval_desemp');
            $table->bigInteger('salario');
            $table->text('dato_adic');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('post_exp_laborales');
    }
}
