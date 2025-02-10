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
        Schema::create('ventas', function (Blueprint $table) {
            $table->bigIncrements('id_venta');
            $table->string('fecha_creacion');
            $table->string('total');
            $table->bigInteger('id_empleado')->unsigned();
            $table->bigInteger('id_cliente')->unsigned();

            $table->foreign('id_cliente')->references('id_cliente')->on('clientes');
            $table->foreign('id_empleado')->references('id_empleado')->on('empleados');

            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {

        Schema::table('ventas', function (Blueprint $table) {
            $table->dropForeign(['id_cliente']);
            $table->dropForeign(['id_empleado']);
        });
        Schema::dropIfExists('ventas');
    }
};
