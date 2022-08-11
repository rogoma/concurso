<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDatosPersonalesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $tableName = "datos_personales";
        Schema::create($tableName, function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('usuario_id');
            $table->foreign('usuario_id', 'fk_usuario_id_dat_pers')->references('id')->on('usuarios')->onDelete('restrict')->onUpdate('restrict');
            $table->unsignedBigInteger('tipo_doc_id');
            $table->foreign('tipo_doc_id', 'fk_dat_personal_tipo_doc_id')->references('id')->on('tipo_docs')->onDelete('restrict')->onUpdate('restrict');
            $table->string('pdf', 100)->nullable();
            $table->date('fecha');
            $table->text('dato_adic')->nullable();
            $table->string('user_crea', 20);
            $table->timestamp('fecha_crea');
            $table->string('user_mod', 20)->nullable();
            $table->dateTime('fecha_mod')->nullable();
        });

        DB::statement("COMMENT ON TABLE {$tableName} IS 'Tabla de datos personales'");

    }
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('datos_personales');
    }
}
