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
        Schema::create('pedidos', function (Blueprint $table) {
            $table->bigIncrements('id_pedido');
            $table->string('fecha');
            $table->boolean("status");
            $table->string('total')-> nullable();
            $table->bigInteger('id_usuario')->unsigned();
            $table->bigInteger('id_cliente')->unsigned();

            $table->foreign('id_cliente')->references('id_cliente')->on('clientes');
            $table->foreign('id_usuario')->references('id_usuario')->on('usuarios');


            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pedidos');
    }
};
