<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTipoRechazoDocsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $tableName = "tipo_rechazo_docs";
        Schema::create($tableName, function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('tipo_doc_id');
            $table->foreign('tipo_doc_id', 'fk_tipo_doc_id')->references('id')->on('tipo_docs')->onDelete('restrict')->onUpdate('restrict');
            $table->string('rechazo', 60);
            $table->string('user_crea', 20);
            $table->timestamp('fecha_crea');
            $table->string('user_mod', 20)->nullable();
            $table->dateTime('fecha_mod')->nullable();
        });

        DB::statement("COMMENT ON TABLE {$tableName} IS 'Tabla de Tipos de Rechazo de Documentos. Está vinculado a 1 documento de la tabla Tipo_doc. Ej: Falta de Firma, Vencimiento de CI, Etc...'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tipo_rechazo_docs');
    }
}
