<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreVentaRequest;
use App\Http\Requests\UpdateVentaRequest;
use App\Models\Producto;
use App\Models\Venta;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class VentaController extends Controller
{
    public function index()
    {
        $this->authorize('viewAny', Venta::class);

        $ventas = auth()->user()->esCliente()
            ? Venta::with(['producto', 'cliente', 'vendedor'])->where('cliente_id', auth()->id())->latest()->paginate(10)
            : Venta::with(['producto', 'cliente', 'vendedor'])->latest()->paginate(10);

        return view('ventas.index', compact('ventas'));
    }

    public function create()
    {
        $this->authorize('create', Venta::class);

        $productos = Producto::with('usuario')->where('existencia', '>', 0)->latest()->get();

        return view('ventas.create', compact('productos'));
    }

    public function store(StoreVentaRequest $request)
    {
        $this->authorize('create', Venta::class);

        $producto = Producto::with('usuario')->findOrFail($request->validated('producto_id'));
        $cantidad = (int) $request->validated('cantidad');

        if ($producto->existencia < $cantidad) {
            return back()->withErrors([
                'cantidad' => 'No hay existencia suficiente para completar la compra.',
            ])->withInput();
        }

        $total = $producto->precio * $cantidad;

        try {
            DB::beginTransaction();

            $venta = Venta::create([
                'producto_id' => $producto->id,
                'vendedor_id' => $producto->usuario_id,
                'cliente_id' => auth()->id(),
                'fecha' => $request->validated('fecha') ?? now()->toDateString(),
                'cantidad' => $cantidad,
                'total' => $total,
            ]);

            $producto->decrement('existencia', $cantidad);

            DB::commit();

            Log::channel('ventas')->info('Venta creada', [
                'venta_id' => $venta->id,
                'cliente_id' => auth()->id(),
                'vendedor_id' => $producto->usuario_id,
                'producto_id' => $producto->id,
                'total' => $total,
            ]);

            return redirect()->route('ventas.show', $venta)->with('success', 'Venta registrada correctamente.');
        } catch (\Throwable $exception) {
            DB::rollBack();

            Log::channel('ventas')->error('Error al crear venta', [
                'mensaje' => $exception->getMessage(),
            ]);

            return back()->with('error', 'Ocurrio un error al procesar la venta.');
        }
    }

    public function show(Venta $venta)
    {
        $this->authorize('view', $venta);

        $venta->load(['producto', 'cliente', 'vendedor']);

        return view('ventas.show', compact('venta'));
    }

    public function edit(Venta $venta)
    {
        $this->authorize('update', $venta);

        $venta->load(['producto', 'cliente', 'vendedor']);

        return view('ventas.edit', compact('venta'));
    }

    public function update(UpdateVentaRequest $request, Venta $venta)
    {
        $this->authorize('update', $venta);

        $venta->update($request->validated());

        return redirect()->route('ventas.show', $venta)->with('success', 'Venta actualizada correctamente.');
    }

    public function destroy(Venta $venta)
    {
        $this->authorize('delete', $venta);

        try {
            DB::beginTransaction();

            $venta->producto->increment('existencia', $venta->cantidad);
            $venta->delete();

            DB::commit();

            return redirect()->route('ventas.index')->with('success', 'Venta eliminada correctamente.');
        } catch (\Throwable $exception) {
            DB::rollBack();

            return back()->with('error', 'No fue posible eliminar la venta.');
        }
    }
}
