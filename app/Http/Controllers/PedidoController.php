<?php

namespace App\Http\Controllers;

use App\Http\Responses\ApiResponses;
use App\Models\Pedido;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PedidoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        
        try {
            $productos = Pedido::all();
    
            return ApiResponses::success(
                $productos->isEmpty() ? 'No se encontraron pedidos' : 'Pedidos encontrados',
                200,
                $productos
            );
        } catch (Exception $e) {
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
            'fecha' => 'required',
            'status' => 'required',
            
        ]);

        if ($validator->fails()) {
            $data = [
                'message' => 'Error en la validacion de datos',
                'errors' => $validator->errors(),
                'status' => 400
            ];
            return response()->json($data, 400);
        }

        $pedido = Pedido::create([

            'fecha'=> $request->fecha,
            'status' => $request->nombre,
            "total" => $request->dimension,
            "id_empleado" => $request->id_empleado,
            "id_cliente" => $request->id_cliente,
           
        ]);

        if (!$pedido) {
            $data = [
                'message' => 'Error al crear pedido',
                'status' => 500
            ];
            return response()->json($data, 500);
        }

        return response()->json([
            'Pedido' => $pedido,
            'message' => 'pedido creado exitosamente',
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
            $pedido = Pedido::findOrFail($id);
    
         
            return ApiResponses::success('Pedido encontrada', 200, $pedido);
    
        } catch (ModelNotFoundException $e) {
            // Si no se encuentra la categoría, devolver un error 404
            return ApiResponses::error('Pedido no encontrado', 404);
    
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
            'fecha' => 'required',
            'status' => 'required',
            'total' => 'required',
            ]);
    
            // Buscar al cliente por su ID
            $pedido = Pedido::findOrFail($id);
    
            // Actualizar los campos del cliente
            $pedido->fecha = $request->fecha;
            $pedido->status = $request->status;
            $pedido->total = $request->total;
            $pedido->id_empleado = $request->id_empleado;
            $pedido->id_cliente = $request->id_cliente;
            
    
            // Guardar los cambios en la base de datos
            $pedido->save();
    
            // Respuesta exitosa, cliente actualizado
            return ApiResponses::success('Pedido actualizado exitosamente', 200,  $pedido);
    
        } catch (ModelNotFoundException $e) {
            // Si el cliente no se encuentra, devolver un error 404
            return ApiResponses::error('Pedido no encontrado', 404);
    
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
            $pedido = Pedido::findOrFail($id);
            
            // Eliminar el cliente
            $pedido->delete();
    
            // Respuesta exitosa: cliente eliminado
            return ApiResponses::success('pedido eliminado exitosamente', 200);
    
        } catch (ModelNotFoundException $e) {
            // Si el cliente no se encuentra, devolver un error 404
            return ApiResponses::error('Pedido no encontrado', 404);
    
        } catch (Exception $e) {
            // Captura cualquier otro tipo de error y devuelve un error genérico
            return ApiResponses::error('Error al eliminar pedido: ' . $e->getMessage(), 500);
        }


    }


    
}


