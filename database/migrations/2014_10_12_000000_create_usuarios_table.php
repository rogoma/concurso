<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsuariosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $tableName = "usuarios";
        Schema::create($tableName, function (Blueprint $table) {
            $table->id();
            $table->string('ci', 12)->unique();
            $table->string('email', 60)->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->text('two_factor_secret')->nullable();
            $table->text('two_factor_recovery_codes')->nullable();
            $table->rememberToken();
            $table->boolean('activo')->default(0);
            $table->string('user_crea', 20);
            $table->timestamp('fecha_crea');
            $table->string('user_mod', 20)->nullable();
            $table->dateTime('fecha_mod')->nullable();
        });

        DB::statement("COMMENT ON TABLE {$tableName} IS 'Tabla de Usuarios. usuario = Nro. de CI; CI = char porque puede ser extranjero; activo = S/N'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('usuarios');
    }
}
