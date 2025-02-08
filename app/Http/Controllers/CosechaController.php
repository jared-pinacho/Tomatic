<?php

namespace App\Http\Controllers;

use App\Http\Responses\ApiResponses;
use App\Models\Cosecha;
use App\Models\Invernadero;
use Dotenv\Validator;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator as FacadesValidator;

class CosechaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            // Recuperar todas las categorías
            $cosechas = Cosecha::all();

            // Verifica si hay categorías para evitar respuestas vacías innecesarias
            if ($cosechas->isEmpty()) {
                return ApiResponses::error('No se encontraron cosechas', 404);
            }

            return ApiResponses::success('cosechas encontradas', 200, $cosechas);
        } catch (Exception $e) {
            // Captura errores generales
            return ApiResponses::error('Error interno: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
{
    // Validación de los datos
    $validator = FacadesValidator::make($request->all(), [
        'fecha_inicio' => 'required',
        'id_invernadero' => 'required|exists:invernaderos,id_invernadero', // Verifica que el id_invernadero exista
    ]);

    if ($validator->fails()) {
        return response()->json([
            'message' => 'Error en la validación de datos',
            'errors' => $validator->errors(),
            'status' => 400
        ], 400);
    }

    try {
        // Buscar el invernadero y concatenar nombre con fecha de inicio
        $invernadero = Invernadero::where('id_invernadero', $request->id_invernadero)->firstOrFail();
        $nombre_inv = $invernadero->nombre;
        $nombre_cosecha = $nombre_inv . ' - ' . $request->fecha_inicio;

        // Crear la cosecha
        $cosecha = Cosecha::create([
            'nombre' => $nombre_cosecha,
            'fecha_inicio' => $request->fecha_inicio,
            'id_invernadero' => $request->id_invernadero,
            'fecha_final' => null,   // null si no está asignado
            'monto_total' => "0",      // Valor numérico
            'estado' => 0,           // Estado inicial
        ]);

        return response()->json([
            'cosecha' => $cosecha,
            'message' => 'Cosecha creada exitosamente',
            'status' => 201
        ], 201);

    } catch (ModelNotFoundException $e) {
        return response()->json([
            'message' => 'Invernadero no encontrado',
            'status' => 404
        ], 404);
    } catch (Exception $e) {
        return response()->json([
            'message' => 'Error al crear la cosecha: ' . $e->getMessage(),
            'status' => 500
        ], 500);
    }
}


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            
            // Buscar la categoría por ID
            $cosecha = Cosecha::findOrFail($id);
    
         
            return ApiResponses::success('Cosecha encontrada', 200, $cosecha);
    
        } catch (ModelNotFoundException $e) {
            // Si no se encuentra la categoría, devolver un error 404
            return ApiResponses::error('Cosecha no encontrada', 404);
    
        } catch (Exception $e) {
            // Captura cualquier otro error y devolver una respuesta de error general
            return ApiResponses::error('Error interno: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
            // Buscar al cliente por su ID
            $cosecha= Cosecha::findOrFail($id);
        
        try {
           
            $invernadero = Invernadero::where('id_invernadero', $request->id_invernadero)->firstOrFail();
            $nombre_inv = $invernadero->nombre;
            $nombre_cosecha = $nombre_inv . ' - ' . $request->fecha_inicio;

            $cosecha->nombre = $nombre_cosecha;
            $cosecha->fecha_inicio = $request->fecha_inicio;
            $cosecha->id_invernadero = $request->id_invernadero;
            $cosecha->fecha_final = $request->fecha_final;
            $cosecha->monto_total = $request->monto_total;
            $cosecha->estado = $request->estado;

    
            // Guardar los cambios en la base de datos
            $cosecha->save();
    
            // Respuesta exitosa, cliente actualizado
            return ApiResponses::success('cosecha actualizada exitosamente', 200,  $invernadero);
    
        } catch (ModelNotFoundException $e) {
            // Si el cliente no se encuentra, devolver un error 404
            return ApiResponses::error('cosecha no encontrada', 404);
    
        } catch (Exception $e) {
            // Captura cualquier otro tipo de error
            return ApiResponses::error('Error interno: ' . $e->getMessage(), 500);
        }


    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        
        try {
            // Buscar al cliente por su ID
            $cosecha = Cosecha::findOrFail($id);
            
            // Eliminar el cliente
            $cosecha->delete();
    
            // Respuesta exitosa: cliente eliminado
            return ApiResponses::success('cosecha eliminada exitosamente', 200);
    
        } catch (ModelNotFoundException $e) {
            // Si el cliente no se encuentra, devolver un error 404
            return ApiResponses::error('Cosecha no encontrada', 404);
    
        } catch (Exception $e) {
            // Captura cualquier otro tipo de error y devuelve un error genérico
            return ApiResponses::error('Error al eliminar Invernadero: ' . $e->getMessage(), 500);
        }


    }
}
