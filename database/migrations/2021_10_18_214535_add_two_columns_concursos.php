<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTwoColumnsConcursos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('concursos', function (Blueprint $table) {
            $table->string('perfil', 300)->after('salario')->nullable();
            $table->string('proceso', 300)->after('perfil')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('concursos', function (Blueprint $table) {
            $table->dropColumn('proceso');
            $table->dropColumn('perfil');
        });
    }
}
