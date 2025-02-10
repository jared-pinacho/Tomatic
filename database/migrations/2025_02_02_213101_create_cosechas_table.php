<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('cosechas', function (Blueprint $table) {
            $table->bigIncrements('id_cosecha');
            $table->string('fecha_inicio');
            $table->string('fecha_final')->nullable();
            $table->string('nombre');
            $table->integer('estado');
            $table->string('monto_total')->nullable();

            $table->bigInteger('id_invernadero')->unsigned();

            
            $table->foreign('id_invernadero')->references('id_invernadero')->on('invernaderos');

            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cosechas');
    }
};
