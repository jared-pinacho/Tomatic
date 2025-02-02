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
        Schema::create('productos', function (Blueprint $table) {
            $table->bigIncrements('id_producto');
            $table->string('precio');
            $table->string('tipo');

            $table->bigInteger('id_cosecha')->unsigned();
            $table->bigInteger('id_cliente')->unsigned();
            $table->bigInteger('id_categoria')->unsigned();

            $table->foreign('id_cliente')->references('id_cliente')->on('clientes');
            $table->foreign('id_cosecha')->references('id_usuario')->on('usuarios');
            $table->foreign('id_categoria')->references('id_categoria')->on('categorias');

            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('productos');
    }
};
