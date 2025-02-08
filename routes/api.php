<?php

use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\CosechaController;
use App\Http\Controllers\InvernaderoController;
use App\Http\Controllers\PedidoController;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\UsuarioController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


// CRUD usuarios
Route::resource('usuario',UsuarioController::class)
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
Route::resource('producto',ProductoController::class)
->except(['edit','create']);


