<?php

namespace App\Http\Controllers;


use App\Models\Empleado;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use App\Http\Responses\ApiResponses;
use Illuminate\Support\Facades\Validator;

use Exception;

use Illuminate\Support\Facades\Hash;

class EmpleadoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        try {
            $empleados= Empleado::all();

            return ApiResponses::success('Encontrado', 200, $empleados);
        } catch (ModelNotFoundException $e) {
            return ApiResponses::error('Error: empleados no encontrados', 404);
        } catch (Exception $e) {
            // Handle general exceptions (e.g., database errors)
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
            'edad' => 'required',
            'sexo' => 'required',
            'rol' => 'required',
        ]);

        if ($validator->fails()) {

            $data = [
                'message' => 'Error en la validacion de datos',
                'errors' => $validator->errors(),
                'status' => 400
            ];

            return response()->json($data, 400);
        }


        $empleado = Empleado::create([
            'nombre' => $request->nombre,
            "apellido" => $request->apellido,
            "edad" => $request->edad,
            "sexo" => $request->sexo,
            "rol" => $request->rol,
        ]);

        if (!$empleado) {
            $data = [
                'message' => 'Error al crear empleado',
                'status' => 500
            ];
            return response()->json($data,500);
        }

        return response()->json([
            'empleado' => $empleado,
            'message' => 'Empleado creado exitosamente',
            'status' => 201
        ], 201);

       
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {

        try {
            $empleado = Empleado::where('id_empleado', $id)->firstOrFail();

            return ApiResponses::success('Encontrado', 200, $empleado);
        } catch (ModelNotFoundException $e) {
            return ApiResponses::error('Error: empleado no encontrado', 404);
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
            'edad' => 'required',
            'sexo' => 'required',
            'rol' => 'required',
            ]);

            $empleado = Empleado::findOrFail($id);

            $empleado->nombre = $request->nombre;
            $empleado->apellido = $request->apellido;
            $empleado->edad = $request->edad;
            $empleado->sexo = $request->sexo;
            $empleado->rol = $request->rol;

            $empleado->save();

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
            $empleado = Empleado::findOrFail($id);
            $empleado->delete();
            return ApiResponses::success('Empleado Eliminado', 201);
        } catch (ModelNotFoundException $e) {
            return ApiResponses::error('Error al eliminar empleado: ' . $e->getMessage(), 404);
        }
    }
}
