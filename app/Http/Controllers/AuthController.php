<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Responses\ApiResponses;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Email;

class AuthController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            // Recuperar todas las categorías
            $users = User::all();

            // Verifica si hay categorías para evitar respuestas vacías innecesarias
            if ($users->isEmpty()) {
                return ApiResponses::error('No se encontraron usuarios', 404);
            }

            return ApiResponses::success('usuarios encontrados', 200, $users);
        } catch (Exception $e) {
            // Captura errores generales
            return ApiResponses::error('Error interno: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create() {}

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:50 ',
            'email' => 'required|string|email|max:100|unique:users',
            'password' => 'required',
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


        $user = User::create([
            'name' => $request->name,
            "email" => $request->email,
            'password' => Hash::make($request->password), // Encriptar la contraseña
            'rol' => $request-> rol
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Usuario creado exitosamente',
            'token' => $user->createToken('API TOKEN')->plainTextToken,
        ], 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }


    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email',
            'password' => 'required',
        ]);

        if ($validator->fails()) {

            $data = [

                'message' => 'Error en la validacion de datos',
                'errors' => $validator->errors(),
                'status' => 400
            ];

            return response()->json($data, 400);
        }

        if (!Auth::attempt($request->only('email', 'password'))) {
            return response()->json([
                'status' => false,
                'errors' => ['Unauthorized']
            ], 401);
        }

        $user = User::where('email', $request->email)->first();
        return response()->json([
            'status' => true,
            'message' => 'usuario logeado exitosamente',
            'data' => $user,
            'token' => $user->createToken('API TOKEN')->plainTextToken
        ], 200);
    }


    public function logout(Request $request)
{

    $request->user()->tokens()->delete();


    return response()->json([
        'status' => true,
        'message' => 'Sesión cerrada exitosamente',
    ], 200);
  
}


public function destroy(string $id)
{
    
    try {
        // Buscar al cliente por su ID
        $user = User::findOrFail($id);
        
        // Eliminar el cliente
        $user->delete();

        // Respuesta exitosa: cliente eliminado
        return ApiResponses::success('usuario eliminado exitosamente', 200);

    } catch (ModelNotFoundException $e) {
        // Si el cliente no se encuentra, devolver un error 404
        return ApiResponses::error('Usuario no encontrado', 404);

    } catch (Exception $e) {
        // Captura cualquier otro tipo de error y devuelve un error genérico
        return ApiResponses::error('Error al eliminar usuario: ' . $e->getMessage(), 500);
    }


}




}
