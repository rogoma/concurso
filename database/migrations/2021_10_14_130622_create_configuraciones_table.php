<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateConfiguracionesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('configuraciones', function (Blueprint $table) {
            $table->id();
            $table->string('razon_social', 100)->nullable();
            $table->string('ruc', 25)->nullable();
            $table->string('telefono', 20)->nullable();
            $table->string('email', 50)->nullable();
            $table->string('direccion', 100)->nullable();
            $table->string('dpto', 60)->nullable();
            $table->string('ciudad', 60)->nullable();
            $table->boolean('habilitacion_ext')->dafualt(0);
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
        Schema::dropIfExists('configuraciones');
    }
}
