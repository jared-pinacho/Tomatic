<?php

namespace App\Http\Controllers;

use App\Http\Responses\ApiResponses;
use App\Models\Producto;
use App\Models\Venta;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProductoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $productos = Producto::all();
        
            // Si la colección está vacía, devuelve un error
            if ($productos->isEmpty()) {
                return ApiResponses::error('No se encontraron productos', 404);
            }
        
            return ApiResponses::success('Productos encontrados', 200, $productos);
        } catch (\Exception $e) {
            // Captura errores generales si algo sale mal
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
      
        $nombreP = $request->nombre .' - 1 '.$request->tipo;

        // Validación de los datos
    $validator = Validator::make($request->all(), [
        'nombre' => 'required',
        'precio' => 'required',
        'tipo' => 'required',
        'id_cosecha' => 'required',
        'id_categoria' => 'required'
    ]);

    if ($validator->fails()) {
        return response()->json([
            'message' => 'Error en la validación de datos',
            'errors' => $validator->errors(),
            'status' => 400
        ], 400);
    }

    try {
        
        // Crear la cosecha
        $producto = Producto::create([
            'nombre' => $nombreP,
            'precio' => $request->precio,
            'tipo' => $request->tipo,
            'id_cosecha' => $request->id_cosecha,
            'id_categoria' => $request->id_categoria,

        ]);

        return response()->json([
            'producto' => $producto,
            'message' => 'Producto creado exitosamente',
            'status' => 201
        ], 201);

    } catch (ModelNotFoundException $e) {
        return response()->json([
            'message' => 'Producto no encontrado',
            'status' => 404
        ], 404);
    } catch (Exception $e) {
        return response()->json([
            'message' => 'Error al crear el producto: ' . $e->getMessage(),
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
            $producto = Producto::findOrFail($id);
    
         
            return ApiResponses::success('Producto encontrado', 200, $producto);
    
        } catch (ModelNotFoundException $e) {
            // Si no se encuentra la categoría, devolver un error 404
            return ApiResponses::error('Producto no encontrado', 404);
    
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
        'precio' => 'required',
        'tipo' => 'required',
      
            ]);
    
            // Buscar al cliente por su ID
            $producto = Producto::findOrFail($id);

            // Actualizar los campos del cliente
            $producto->nombre = $request->nombre;
            $producto->precio = $request->precio;
            $producto->tipo = $request->tipo;
            $producto->id_cosecha = $request->id_cosecha;
            $producto->id_categoria = $request->id_categoria;
            
    
            // Guardar los cambios en la base de datos
            $producto->save();
    
            // Respuesta exitosa, cliente actualizado
            return ApiResponses::success('Producto actualizado exitosamente', 200,  $producto);
    
        } catch (ModelNotFoundException $e) {
            // Si el cliente no se encuentra, devolver un error 404
            return ApiResponses::error('Producto no encontrado', 404);
    
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
            $producto = Producto::findOrFail($id);
            
            // Eliminar el cliente
            $producto->delete();
    
            // Respuesta exitosa: cliente eliminado
            return ApiResponses::success('Producto eliminado exitosamente', 200);
    
        } catch (ModelNotFoundException $e) {
            // Si el cliente no se encuentra, devolver un error 404
            return ApiResponses::error('Producto no encontrado', 404);
    
        } catch (Exception $e) {
            // Captura cualquier otro tipo de error y devuelve un error genérico
            return ApiResponses::error('Error al eliminar producto: ' . $e->getMessage(), 500);
        }
    }
}
