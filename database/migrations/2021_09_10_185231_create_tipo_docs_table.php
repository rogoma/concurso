<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTipoDocsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $tableName = "tipo_docs";
        Schema::create($tableName, function (Blueprint $table) {
            $table->id();
            $table->string('tipo', 1);
            $table->string('documento', 50)->unique();
            $table->string('user_crea', 20);
            $table->timestamp('fecha_crea');
            $table->string('user_mod', 20)->nullable();
            $table->dateTime('fecha_mod')->nullable();
        });

        DB::statement("COMMENT ON TABLE {$tableName} IS 'Tabla de Tipo de Documento: CI, Ant. Judiciales, Ant. Policiales, Etc. Tipo = P/A (Personal/Academico)'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tipo_docs');
    }
}
