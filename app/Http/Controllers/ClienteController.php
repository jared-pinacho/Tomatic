<?php

namespace App\Http\Controllers;
use App\Models\Cliente;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Http\Responses\ApiResponses;
use Illuminate\Support\Facades\Validator;
use Exception;

use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;

class ClienteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $clientes = Cliente::all();
    
            return ApiResponses::success(
                $clientes->isEmpty() ? 'No se encontraron clientes' : 'Clientes encontrados',
                200,
                $clientes
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
            'nombre' => 'required',
            'apellido' => 'required',
            'telefono' => 'required',
            
        ]);

        if ($validator->fails()) {

            $data = [

                'message' => 'Error en la validacion de datos',
                'errors' => $validator->errors(),
                'status' => 400
            ];

            return response()->json($data, 400);
        }


        $cliente = Cliente::create([
            'nombre' => $request->nombre,
            "apellido" => $request->apellido,
            "telefono" => $request->telefono,
            
        ]);

        if (!$cliente) {
            $data = [
                'message' => 'Error al crear cliente',
                'status' => 500
            ];
            return response()->json($data,500);
        }

        return response()->json([
            'cliente' => $cliente,
            'message' => 'cliente creado exitosamente',
            'status' => 201
        ], 201);

       


    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $cliente = Cliente::where('id_cliente', $id)->firstOrFail();

            return ApiResponses::success('Encontrado', 200, $cliente);
        } catch (ModelNotFoundException $e) {
            return ApiResponses::error('Error: cliente no encontrado', 404);
        } catch (Exception $e) {
            // Handle general exceptions (e.g., database errors)
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
            $request->validate([
                'nombre' => 'required',
                'apellido' => 'required',
                'telefono' => 'required',
                
            ]);

            $cliente = Cliente::findOrFail($id);

            $cliente->nombre = $request->nombre;
            $cliente->apellido = $request->apellido;
            $cliente->telefono = $request->telefono;
           

            $cliente->save();

            return ApiResponses::success('Actualizado', 201);
        } catch (ModelNotFoundException $e) {
            return ApiResponses::error('No encontrado', 404);
        } catch (Exception $e) {
            return ApiResponses::error('Error: ' . $e->getMessage(), 500);
        }




    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        
        try {
            $cliente = Cliente::findOrFail($id);
            $cliente->delete();
            return ApiResponses::success('cliente Eliminado', 201);
        } catch (ModelNotFoundException $e) {
            return ApiResponses::error('Error al eliminar cliente: ' . $e->getMessage(), 404);
        }


    }
}
