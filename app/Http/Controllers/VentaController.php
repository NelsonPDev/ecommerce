<?php

namespace App\Http\Controllers;

use App\Models\Venta;
use App\Models\Producto;
use App\Http\Requests\ComprarProductoRequest;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class VentaController extends Controller
{
    /**
     * Mostrar historial de ventas del usuario autenticado
     */
    public function index()
    {
        $ventas = auth()->user()->ventas()->with('producto')->paginate(10);
        return view('ventas.index', compact('ventas'));
    }

    /**
     * Procesar compra de producto (solo cliente)
     */
    public function comprar(ComprarProductoRequest $request)
    {
        Log::info('Intento de compra por ' . auth()->user()->email);

        $producto = Producto::findOrFail($request->producto_id);
        $cantidad = $request->cantidad;

        // Validar stock
        if ($producto->stock < $cantidad) {
            Log::warning('Stock insuficiente para ' . $producto->nombre . ' - solicitado: ' . $cantidad . ', disponible: ' . $producto->stock);

            return back()->with('error', 'Stock insuficiente para este producto');
        }

        // Usar transacción para asegurar integridad
        DB::beginTransaction();

        try {
            $total = $producto->precio * $cantidad;

            // Crear venta
            $venta = Venta::create([
                'usuario_id' => auth()->id(),
                'producto_id' => $producto->id,
                'cantidad' => $cantidad,
                'precio_unitario' => $producto->precio,
                'total' => $total,
                'estado' => 'completada',
            ]);

            // Descontar stock
            $producto->decrement('stock', $cantidad);

            DB::commit();

            Log::info('Venta exitosa: Usuario=' . auth()->user()->email . ', Producto=' . $producto->nombre . ', Cantidad=' . $cantidad . ', Total=' . $total);

            return redirect()->route('ventas.index')->with('success', 'Compra realizada exitosamente');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error en compra: ' . $e->getMessage());

            return back()->with('error', 'Error al procesar la compra');
        }
    }

    /**
     * Ver detalles de una venta
     */
    public function show(Venta $venta)
    {
        // Verificar que la venta pertenece al usuario actual
        if ($venta->usuario_id !== auth()->id()) {
            abort(403, 'No autorizado');
        }

        Log::info('Detalles de venta visualizados por ' . auth()->user()->email);

        return view('ventas.show', compact('venta'));
    }

    /**
     * Cancelar venta (solo el usuario propietario)
     */
    public function cancelar(Venta $venta)
    {
        if ($venta->usuario_id !== auth()->id()) {
            abort(403, 'No autorizado');
        }

        if ($venta->estado === 'cancelada') {
            return back()->with('info', 'Esta venta ya está cancelada');
        }

        DB::beginTransaction();

        try {
            // Devolver stock
            $venta->producto->increment('stock', $venta->cantidad);

            // Actualizar estado
            $venta->update(['estado' => 'cancelada']);

            DB::commit();

            Log::info('Venta cancelada: Usuario=' . auth()->user()->email . ', Venta ID=' . $venta->id);

            return redirect()->route('ventas.index')->with('success', 'Venta cancelada correctamente');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error al cancelar venta: ' . $e->getMessage());

            return back()->with('error', 'Error al cancelar la venta');
        }
    }
}
