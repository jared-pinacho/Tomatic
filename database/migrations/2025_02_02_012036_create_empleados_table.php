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
        Schema::create('empleados', function (Blueprint $table) {
            $table->bigIncrements('id_empleado');
            $table->string("nombre");
            $table->string("apellido");
            $table->string("edad");
            $table->string("sexo");
            $table->integer("rol");
            $table->BigInteger('id_user')->unsigned()->nullable(); // Hacemos que la columna sea opcional
            $table->foreign('id_user')->references('id')->on('users')->onDelete('set null'); // Si se elimina eio, setea el valor a null



            $table->timestamps();

            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('empleados');
    }
};
