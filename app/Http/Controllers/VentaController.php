<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddToCartRequest;
use App\Http\Requests\ProcessCheckoutRequest;
use App\Http\Requests\StoreVentaRequest;
use App\Http\Requests\UpdateVentaRequest;
use App\Models\Producto;
use App\Models\Usuario;
use App\Models\Venta;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

class VentaController extends Controller
{
    public function index(): View
    {
        $this->authorize('viewAny', Venta::class);
        $usuario = auth()->user();

        $ventas = $usuario->esCliente()
            ? Venta::with(['producto', 'cliente', 'vendedor'])->where('cliente_id', $usuario->id)->latest()->paginate(10)
            : Venta::with(['producto', 'cliente', 'vendedor'])->latest()->paginate(10);

        return view('ventas.index', compact('ventas'));
    }

    public function create()
    {
        $this->authorize('create', Venta::class);

        $productos = Producto::with('usuario')->get();
        $clientes = [];

        if (auth()->user()->esAdministrador()) {
            $clientes = Usuario::where('rol', 'cliente')->get();
        }

        return view('ventas.create', compact('productos', 'clientes'));
    }

    public function store(StoreVentaRequest $request)
    {
        $this->authorize('create', Venta::class);
        $usuario = auth()->user();

        $producto = Producto::with('usuario')->findOrFail($request->validated('producto_id'));
        $cantidad = (int) $request->validated('cantidad');

        if ($producto->existencia < $cantidad) {
            return back()->withErrors([
                'cantidad' => 'No hay existencia suficiente para completar la compra.',
            ])->withInput();
        }

        $total = $producto->precio * $cantidad;
        $clienteId = auth()->user()->esAdministrador()
            ? $request->validated('cliente_id') ?? $usuario->id
            : $usuario->id;

        try {
            DB::beginTransaction();

            $venta = Venta::create([
                'producto_id' => $producto->id,
                'vendedor_id' => $producto->usuario_id,
                'cliente_id' => $clienteId,
                'fecha' => $request->validated('fecha') ?? now()->toDateString(),
                'cantidad' => $cantidad,
                'total' => $total,
            ]);

            $producto->decrement('existencia', $cantidad);

            DB::commit();

            Log::channel('ventas')->info('Venta creada', [
                'venta_id' => $venta->id,
                'cliente_id' => $clienteId,
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

    public function cart(): View
    {
        $this->authorize('buy', Producto::class);

        [$items, $total] = $this->cartSummary();

        return view('ventas.cart', compact('items', 'total'));
    }

    public function addToCart(AddToCartRequest $request): RedirectResponse
    {
        $producto = Producto::findOrFail($request->validated('producto_id'));
        $cantidad = (int) $request->validated('cantidad');

        if ($producto->existencia < $cantidad) {
            return back()->withErrors([
                'cantidad' => 'No hay existencia suficiente para agregar esa cantidad.',
            ]);
        }

        $cart = session()->get('cart', []);
        $current = (int) ($cart[$producto->id] ?? 0);
        $newQuantity = $current + $cantidad;

        if ($newQuantity > $producto->existencia) {
            return back()->withErrors([
                'cantidad' => 'La cantidad total en carrito excede la existencia disponible.',
            ]);
        }

        $cart[$producto->id] = $newQuantity;
        session()->put('cart', $cart);

        return back()->with('success', 'Producto agregado al carrito.');
    }

    public function updateCart(\App\Http\Requests\UpdateCartRequest $request, Producto $producto): RedirectResponse
    {
        $this->authorize('buy', Producto::class);

        $cantidad = (int) $request->validated('cantidad');

        if ($cantidad > $producto->existencia) {
            return back()->withErrors([
                'cantidad' => 'No hay existencia suficiente para esa cantidad.',
            ]);
        }

        $cart = session()->get('cart', []);

        if (isset($cart[$producto->id])) {
            $cart[$producto->id] = $cantidad;
            session()->put('cart', $cart);
        }

        return redirect()->route('carrito.index')->with('success', 'Carrito actualizado.');
    }

    public function removeFromCart(Producto $producto): RedirectResponse
    {
        $this->authorize('buy', Producto::class);

        $cart = session()->get('cart', []);
        unset($cart[$producto->id]);
        session()->put('cart', $cart);

        return redirect()->route('carrito.index')->with('success', 'Producto eliminado del carrito.');
    }

    public function checkout(): View|RedirectResponse
    {
        $this->authorize('buy', Producto::class);

        [$items, $total] = $this->cartSummary();

        if (empty($items)) {
            return redirect()->route('productos.index')->with('error', 'Tu carrito esta vacio.');
        }

        return view('ventas.checkout', compact('items', 'total'));
    }

    public function processCheckout(ProcessCheckoutRequest $request): RedirectResponse
    {
        $this->authorize('buy', Producto::class);
        $usuario = auth()->user();

        [$items, $total] = $this->cartSummary();

        if (empty($items)) {
            return redirect()->route('productos.index')->with('error', 'Tu carrito esta vacio.');
        }

        try {
            DB::beginTransaction();

            foreach ($items as $item) {
                $producto = Producto::lockForUpdate()->findOrFail($item['producto']->id);

                if ($producto->existencia < $item['cantidad']) {
                    DB::rollBack();

                    return redirect()->route('carrito.index')->with('error', 'Uno de los productos ya no tiene suficiente existencia.');
                }

                $venta = Venta::create([
                    'producto_id' => $producto->id,
                    'vendedor_id' => $producto->usuario_id,
                    'cliente_id' => $usuario->id,
                    'fecha' => now()->toDateString(),
                    'cantidad' => $item['cantidad'],
                    'total' => $item['subtotal'],
                ]);

                $producto->decrement('existencia', $item['cantidad']);

                Log::channel('ventas')->info('Venta creada', [
                    'venta_id' => $venta->id,
                    'cliente_id' => $usuario->id,
                    'vendedor_id' => $producto->usuario_id,
                    'producto_id' => $producto->id,
                    'total' => $item['subtotal'],
                    'tarjeta_ultimos_4' => substr($request->validated('numero_tarjeta'), -4),
                ]);
            }

            DB::commit();
            session()->forget('cart');

            return redirect()->route('ventas.index')->with('success', 'Compra realizada correctamente. Revisa tu historial.');
        } catch (\Throwable $exception) {
            DB::rollBack();

            Log::channel('ventas')->error('Error al procesar checkout', [
                'mensaje' => $exception->getMessage(),
                'cliente_id' => $usuario->id,
            ]);

            return redirect()->route('carrito.checkout')->with('error', 'No fue posible procesar la compra.');
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

    protected function cartSummary(): array
    {
        $cart = session()->get('cart', []);

        if (empty($cart)) {
            return [[], 0];
        }

        $productos = Producto::with('usuario')->whereIn('id', array_keys($cart))->get()->keyBy('id');
        $items = [];
        $total = 0;

        foreach ($cart as $productoId => $cantidad) {
            $producto = $productos->get((int) $productoId);

            if (! $producto) {
                continue;
            }

            $subtotal = $producto->precio * $cantidad;
            $items[] = [
                'producto' => $producto,
                'cantidad' => $cantidad,
                'subtotal' => $subtotal,
            ];
            $total += $subtotal;
        }

        return [$items, $total];
    }
}
