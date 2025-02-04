<?php

namespace App\Http\Controllers;

use App\Http\Responses\ApiResponses;
use App\Models\Invernadero;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class InvernaderoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            // Recuperar todas las categorías
            $invernaderos = Invernadero::all();

            // Verifica si hay categorías para evitar respuestas vacías innecesarias
            if ($invernaderos->isEmpty()) {
                return ApiResponses::error('No se encontraron invernaderos', 404);
            }

            return ApiResponses::success('Invernaderos encontrados', 200, $invernaderos);
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
        $validator = Validator::make($request->all(), [
            'nombre' => 'required|unique:invernaderos,nombre',
            'dimension' => 'required',
            'fecha_creacion' => 'required',
        ]);

        if ($validator->fails()) {
            $data = [
                'message' => 'Error en la validacion de datos',
                'errors' => $validator->errors(),
                'status' => 400
            ];
            return response()->json($data, 400);
        }

        $invernadero = Invernadero::create([
            'nombre' => $request->nombre,
            "dimension" => $request->dimension,
            "fecha_creacion" => $request->fecha_creacion,
        ]);

        if (!$invernadero) {
            $data = [
                'message' => 'Error al crear invernadero',
                'status' => 500
            ];
            return response()->json($data, 500);
        }

        return response()->json([
            'cliente' => $invernadero,
            'message' => 'invernadero creado exitosamente',
            'status' => 201
        ], 201);

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            // Buscar la categoría por ID
            $invernadero = Invernadero::findOrFail($id);
    
         
            return ApiResponses::success('Invernadero encontrado', 200, $invernadero);
    
        } catch (ModelNotFoundException $e) {
            // Si no se encuentra la categoría, devolver un error 404
            return ApiResponses::error('Invernadero no encontrada', 404);
    
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
        try {
            // Validación de datos de entrada
            $request->validate([
               'nombre' => 'required',
            'dimension' => 'required',
            'fecha_creacion' => 'required',
            ]);
    
            // Buscar al cliente por su ID
            $invernadero = Invernadero::findOrFail($id);
    
            // Actualizar los campos del cliente
            $invernadero->nombre = $request->nombre;
            $invernadero->dimension = $request->dimension;
            $invernadero->fecha_creacion = $request->fecha_creacion;
            
    
            // Guardar los cambios en la base de datos
            $invernadero->save();
    
            // Respuesta exitosa, cliente actualizado
            return ApiResponses::success('Invernadero actualizada exitosamente', 200,  $invernadero);
    
        } catch (ModelNotFoundException $e) {
            // Si el cliente no se encuentra, devolver un error 404
            return ApiResponses::error('Invernadero no encontrado', 404);
    
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
            $invernadero = Invernadero::findOrFail($id);
            
            // Eliminar el cliente
            $invernadero->delete();
    
            // Respuesta exitosa: cliente eliminado
            return ApiResponses::success('Invernadero eliminado exitosamente', 200);
    
        } catch (ModelNotFoundException $e) {
            // Si el cliente no se encuentra, devolver un error 404
            return ApiResponses::error('Invernadero no encontrado', 404);
    
        } catch (Exception $e) {
            // Captura cualquier otro tipo de error y devuelve un error genérico
            return ApiResponses::error('Error al eliminar Invernadero: ' . $e->getMessage(), 500);
        }
    }
}
