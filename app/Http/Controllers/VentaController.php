<?php

namespace App\Http\Controllers;

use App\Http\Responses\ApiResponses;
use App\Models\DetalleVenta;
use App\Models\Venta;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class VentaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        
        try {
            // Recuperar todas las categorías
            $ventas = Venta::all();

            // Verifica si hay categorías para evitar respuestas vacías innecesarias
            if ($ventas->isEmpty()) {
                return ApiResponses::error('No se encontraron ventas', 404);
            }

            return ApiResponses::success('Ventas encontradas', 200, $ventas);
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
    $validated = $request->validate([
        'fecha_creacion' => 'required',
        'total' => 'required',
        'productos' => 'required|array|min:1',
        'cantidad' => 'required|array|min:1',
    ]);

    // Usamos una transacción para garantizar la integridad de los datos
    DB::beginTransaction();
    
    try {
        // Crear la venta
        $venta = Venta::create([
            'fecha_creacion' => $validated['fecha_creacion'],
            'total' => $validated['total'],
        ]);

        // Crear los detalles de la venta
        foreach ($validated['productos'] as $index => $productoId) {
            $detalle = new DetalleVenta();
            $detalle->id_venta = $venta->id_venta;
            $detalle->id_producto = $productoId;
            $detalle->cantidad = $validated['cantidad'][$index];
            $detalle->save();
        }

        // Confirmar la transacción
        DB::commit();

        return response()->json(['mensaje' => 'Venta creada con éxito'], 200);
    } catch (\Exception $e) {
        // Revertir la transacción si algo sale mal
        DB::rollBack();

        return response()->json(['mensaje' => 'Hubo un error al crear la venta', 'error' => $e->getMessage()], 500);
    }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        
        try {
            // Buscar la categoría por ID
            $venta = Venta::findOrFail($id);
    
         
            return ApiResponses::success('Venta encontrada', 200, $venta);
    
        } catch (ModelNotFoundException $e) {
            // Si no se encuentra la categoría, devolver un error 404
            return ApiResponses::error('Venta no encontrada', 404);
    
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
        
        

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        
        try {
            // Buscar al cliente por su ID
            $venta = Venta::findOrFail($id);
            
            // Eliminar el cliente
            $venta->delete();
    
            // Respuesta exitosa: cliente eliminado
            return ApiResponses::success('Venta eliminada exitosamente', 200);
    
        } catch (ModelNotFoundException $e) {
            // Si el cliente no se encuentra, devolver un error 404
            return ApiResponses::error('Venta no encontrada', 404);
    
        } catch (Exception $e) {
            // Captura cualquier otro tipo de error y devuelve un error genérico
            return ApiResponses::error('Error al eliminar venta: ' . $e->getMessage(), 500);
        }




    }
}
