<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePostDatosPersonalesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('post_datos_personales', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('postulacion_id');
            $table->foreign('postulacion_id', 'fk_postulacion_id')->references('id')->on('postulaciones')->onDelete('cascade')->onUpdate('cascade');
            $table->unsignedBigInteger('tipo_doc_id');
            $table->foreign('tipo_doc_id', 'fk_tipo_doc_id')->references('id')->on('tipo_docs')->onDelete('cascade')->onUpdate('cascade');
            $table->string('pdf', 100);
            $table->date('fecha');
            $table->string('institucion', 50);
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
        Schema::dropIfExists('post_datos_personales');
    }
}
