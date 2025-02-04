<?php

use App\Http\Controllers\ClienteController;
use App\Http\Controllers\UsuarioController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::resource('usuario',UsuarioController::class)
->except(['edit','create']);

Route::resource('cliente',ClienteController::class)
->except(['edit','create']);

