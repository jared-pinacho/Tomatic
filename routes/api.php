<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\CosechaController;
use App\Http\Controllers\InvernaderoController;
use App\Http\Controllers\PedidoController;
use App\Http\Controllers\EmpleadoController;
use App\Http\Controllers\ProductoController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');



Route::middleware(['auth:sanctum'])->group(function(){

    Route::post('logout',[AuthController::class,'logout']);

});


Route::post('register',[AuthController::class,'store']);
Route::post('login',[AuthController::class,'login']);



// CRUD empleados
Route::resource('empleado',EmpleadoController::class)
->except(['edit','create']);

// CRUD clientes
Route::resource('cliente',ClienteController::class)
->except(['edit','create']);

// CRUD categorias
Route::resource('categoria',CategoriaController::class)
->except(['edit','create']);

// CRUD invernaderos
Route::resource('invernadero',InvernaderoController::class)
->except(['edit','create']);

Route::get('invernadero/cosechas/{id}', [InvernaderoController::class, 'cosechas']);


// CRUD cosecha
Route::resource('cosecha',CosechaController::class)
->except(['edit','create']);


// CRUD pedido
Route::resource('pedido',PedidoController::class)
->except(['edit','create']);


// CRUD producto
Route::resource('producto', ProductoController::class)
->except(['edit','create']);



