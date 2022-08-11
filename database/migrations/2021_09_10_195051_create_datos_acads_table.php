<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDatosAcadsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $tableName = "datos_acads";
        Schema::create($tableName, function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('usuario_id');
            $table->foreign('usuario_id', 'fk_idusuario_dat_acad')->references('id')->on('usuarios')->onDelete('restrict')->onUpdate('restrict');
            $table->unsignedBigInteger('tipo_doc_id');
            $table->foreign('tipo_doc_id', 'fk_dat_personal_tipo_doc_id')->references('id')->on('tipo_docs')->onDelete('restrict')->onUpdate('restrict');
            $table->string('titulo', 50);
            $table->unsignedInteger('especialidad_id');
            $table->foreign('especialidad_id', 'fk_especialidad_id_cargo')->references('id')->on('cargos')->onDelete('restrict')->onUpdate('restrict');
            $table->string('pdf', 100)->nullable();
            $table->date('fecha_graduac')->nullable();
            $table->string('institucion', 50);
            $table->string('profesion', 100)->nullable();
            $table->text('dato_adic')->nullable();
            $table->string('user_crea', 20);
            $table->timestamp('fecha_crea');
            $table->string('user_mod', 20)->nullable();
            $table->dateTime('fecha_mod')->nullable();
        });

        DB::statement("COMMENT ON TABLE {$tableName} IS 'Tabla de datos académicos'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('datos_acads');
    }
}
