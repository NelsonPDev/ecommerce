<?php

namespace App\Http\Controllers;

use App\Models\Venta;
use App\Models\Producto;
use App\Http\Requests\StoreVentaRequest;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class VentaController extends Controller
{
    /**
     * Mostrar historial de ventas del usuario autenticado
     */
    public function index()
    {
        $ventas = auth()->user()->ventasComoCliente()->with(['producto', 'vendedor'])->paginate(10);
        return view('ventas.index', compact('ventas'));
    }

    /**
     * Procesar compra de producto (solo cliente)
     */
    public function comprar(StoreVentaRequest $request)
    {
        $this->authorize('create', Venta::class);

        $producto = Producto::findOrFail($request->producto_id);
        $cantidad = $request->cantidad ?? 1; // Default to 1 if not provided

        // Validar existencia
        if ($producto->existencia < $cantidad) {
            return back()->with('error', 'Existencia insuficiente para este producto');
        }

        DB::beginTransaction();

        try {
            $total = $producto->precio * $cantidad;

            // Crear venta
            $venta = Venta::create([
                'producto_id' => $producto->id,
                'vendedor_id' => $producto->usuario_id,
                'cliente_id' => auth()->id(),
                'fecha' => now()->toDateString(),
                'total' => $total,
            ]);

            // Descontar existencia
            $producto->decrement('existencia', $cantidad);

            DB::commit();

            Log::channel('ventas')->info('Venta creada', [
                'venta_id' => $venta->id,
                'cliente_id' => auth()->id(),
                'vendedor_id' => $producto->usuario_id,
                'producto_id' => $producto->id,
                'total' => $total,
            ]);

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
        $this->authorize('view', $venta);

        Log::info('Detalles de venta visualizados por ' . auth()->user()->correo);

        return view('ventas.show', compact('venta'));
    }

    /**
     * Cancelar venta (solo el usuario propietario)
     */
    public function cancelar(Venta $venta)
    {
        $this->authorize('cancel', $venta);

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

            Log::info('Venta cancelada: Usuario=' . auth()->user()->correo . ', Venta ID=' . $venta->id);

            return redirect()->route('ventas.index')->with('success', 'Venta cancelada correctamente');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error al cancelar venta: ' . $e->getMessage());

            return back()->with('error', 'Error al cancelar la venta');
        }
    }
}
