<?php

namespace App\Http\Controllers;

use App\Http\Responses\ApiResponses;
use App\Models\Categoria;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CategoriaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            // Recuperar todas las categorías
            $categorias = Categoria::all();

            // Verifica si hay categorías para evitar respuestas vacías innecesarias
            if ($categorias->isEmpty()) {
                return ApiResponses::error('No se encontraron categorías', 404);
            }

            return ApiResponses::success('Categorías encontradas', 200, $categorias);
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
            'nombre' => 'required',
            'descripcion' => 'required',


        ]);

        if ($validator->fails()) {

            $data = [

                'message' => 'Error en la validacion de datos',
                'errors' => $validator->errors(),
                'status' => 400
            ];

            return response()->json($data, 400);
        }

        $categoria = Categoria::create([
            'nombre' => $request->nombre,
            "descripcion" => $request->descripcion,
        ]);

        if (!$categoria) {
            $data = [
                'message' => 'Error al crear categoria',
                'status' => 500
            ];
            return response()->json($data, 500);
        }

        return response()->json([
            'cliente' => $categoria,
            'message' => 'categoria creado exitosamente',
            'status' => 201
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        try {
            // Buscar la categoría por ID
            $categoria = Categoria::findOrFail($id);
    
         
            return ApiResponses::success('Categoría encontrada', 200, $categoria);
    
        } catch (ModelNotFoundException $e) {
            // Si no se encuentra la categoría, devolver un error 404
            return ApiResponses::error('Categoría no encontrada', 404);
    
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
    public function update(Request $request, $id)
{
    try {
        // Validación de datos de entrada
        $request->validate([
            'nombre' => 'required',
            'descripcion' => 'required',
        ]);

        // Buscar al cliente por su ID
        $categoria = Categoria::findOrFail($id);

        // Actualizar los campos del cliente
        $categoria->nombre = $request->nombre;
        $categoria->descripcion = $request->descripcion;
        

        // Guardar los cambios en la base de datos
        $categoria->save();

        // Respuesta exitosa, cliente actualizado
        return ApiResponses::success('Categoria actualizada exitosamente', 200,  $categoria);

    } catch (ModelNotFoundException $e) {
        // Si el cliente no se encuentra, devolver un error 404
        return ApiResponses::error('Categoria no encontrada', 404);

    } catch (Exception $e) {
        // Captura cualquier otro tipo de error
        return ApiResponses::error('Error interno: ' . $e->getMessage(), 500);
    }
}


    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
{
    try {
        // Buscar al cliente por su ID
        $categoria = Categoria::findOrFail($id);
        
        // Eliminar el cliente
        $categoria->delete();

        // Respuesta exitosa: cliente eliminado
        return ApiResponses::success('Categoria eliminada exitosamente', 200);

    } catch (ModelNotFoundException $e) {
        // Si el cliente no se encuentra, devolver un error 404
        return ApiResponses::error('Categoria no encontrado', 404);

    } catch (Exception $e) {
        // Captura cualquier otro tipo de error y devuelve un error genérico
        return ApiResponses::error('Error al eliminar categoria: ' . $e->getMessage(), 500);
    }
}

}
