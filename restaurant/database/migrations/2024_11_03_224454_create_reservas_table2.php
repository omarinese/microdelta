<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reservas', function (Blueprint $table) {
            $table->id(); // id (auto increment)
            $table->string('nombre_cliente', 255); // varchar(255)
            $table->integer('numero_personas'); // int
            $table->date('fecha_reserva'); // date
            $table->enum('estado', ['pendiente', 'inminente', 'entrante']); // enum('pendiente', 'inminente', 'entrante')
            $table->timestamp('created_at')->useCurrent(); // timestamp with CURRENT_TIMESTAMP default
            $table->timestamp('updated_at')->nullable(); // timestamp, can be null
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('reservas');
    }
};
