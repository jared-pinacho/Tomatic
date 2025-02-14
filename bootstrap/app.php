<?php

use App\Http\Middleware\CheckAdminRole;
use App\Http\Middleware\CheckVendedorRole;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
Use Laravel\Sanctum\Http\Middleware\CheckAbilities;
use Laravel\Sanctum\Http\Middleware\CheckForAnyAbility;


return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {


       

        $middleware->statefulApi();
        // Define los alias para los middleware de habilidad de Sanctum
        $middleware->alias([
            'abilities' => CheckAbilities::class,
            'ability' => CheckForAnyAbility::class,
            'checkAdminRole' => CheckAdminRole::class,
            'checkVendedorRole' => CheckVendedorRole::class
        ]);

       
    })
    ->withExceptions(function (Exceptions $exceptions) {
        // Aquí puedes manejar las excepciones si es necesario
    })


   

    ->create();

