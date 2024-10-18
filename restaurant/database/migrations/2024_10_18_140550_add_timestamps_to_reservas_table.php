<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTimestampsToReservasTable extends Migration
{
    public function up()
    {
        Schema::table('reservas', function (Blueprint $table) {
            // Solo añade 'updated_at' si no existe
            if (!Schema::hasColumn('reservas', 'updated_at')) {
                $table->timestamp('updated_at')->nullable();
            }
        });
    }


    public function down()
    {
        Schema::table('reservas', function (Blueprint $table) {
            $table->dropTimestamps(); // Esto eliminará las columnas si revertimos la migración
        });
    }
}
