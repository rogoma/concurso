<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeExpLaboralesNullable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('exp_laborales', function (Blueprint $table) {
            $table->date('fecha_fin')->nullable()->change();
            $table->string('tel_ref_lab2', 20)->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('exp_laborales', function (Blueprint $table) {
            //
        });
    }
}
