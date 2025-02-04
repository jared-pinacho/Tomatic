<?php

namespace App\Http\Controllers;


use App\Models\Usuario;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use App\Http\Responses\ApiResponses;
use Illuminate\Support\Facades\Validator;

use Exception;

use Illuminate\Support\Facades\Hash;

class UsuarioController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        try {
            $usuarios = Usuario::all();

            return ApiResponses::success('Encontrado', 200, $usuarios);
        } catch (ModelNotFoundException $e) {
            return ApiResponses::error('Error: usuarios no encontrados', 404);
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
            'username' => 'required|unique:usuarios,username',
            'password' => 'required',
            'rol' => 'required'
        ]);

        if ($validator->fails()) {

            $data = [

                'message' => 'Error en la validacion de datos',
                'errors' => $validator->errors(),
                'status' => 400
            ];

            return response()->json($data, 400);
        }


        $usuario = Usuario::create([
            'nombre' => $request->nombre,
            "apellido" => $request->apellido,
            "username" => $request->username,
            'password' => Hash::make($request->password), // Encriptar la contraseña
            "rol" => $request->rol
        ]);

        if (!$usuario) {
            $data = [
                'message' => 'Error al crear usuario',
                'status' => 500
            ];
            return response()->json($data,500);
        }

        return response()->json([
            'usuario' => $usuario,
            'message' => 'Usuario creado exitosamente',
            'status' => 201
        ], 201);

       
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {

        try {
            $usuario = Usuario::where('id_usuario', $id)->firstOrFail();

            return ApiResponses::success('Encontrado', 200, $usuario);
        } catch (ModelNotFoundException $e) {
            return ApiResponses::error('Error: usuario no encontrado', 404);
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
                'username' => 'required',
                'password' => 'required',
                'rol' => 'required',
            ]);

            $usuario = Usuario::findOrFail($id);

            $usuario->nombre = $request->nombre;
            $usuario->apellido = $request->apellido;
            $usuario->username = $request->username;
            $usuario->password = Hash::make($request->password);
            $usuario->rol = $request->rol;

            $usuario->save();

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
            $usuario = Usuario::findOrFail($id);
            $usuario->delete();
            return ApiResponses::success('Usuario Eliminado', 201);
        } catch (ModelNotFoundException $e) {
            return ApiResponses::error('Error al eliminar usuario: ' . $e->getMessage(), 404);
        }
    }
}
