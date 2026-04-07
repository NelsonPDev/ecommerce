<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use App\Models\Categoria;
use App\Http\Requests\UpdateProductoRequest;
use Illuminate\Support\Facades\Log;

class ProductoController extends Controller
{
    /**
     * Listar todos los productos (publicado)
     */
    public function index()
    {
        Log::info('Usuario ' . (auth()->user()?->correo ?? 'anónimo') . ' visualizó catálogo de productos');

        $productos = Producto::with('categorias')->paginate(12);
        return view('productos.index', compact('productos'));
    }

    /**
     * Ver detalles de un producto
     */
    public function show(Producto $producto)
    {
        Log::info('Producto ' . $producto->nombre . ' consultado por ' . (auth()->user()?->correo ?? 'anónimo'));
        return view('productos.show', compact('producto'));
    }

    /**
     * Mostrar formulario de creación (solo administrador)
     */
    public function create()
    {
        $this->authorize('create', Producto::class);
        $categorias = Categoria::all();
        return view('productos.create', compact('categorias'));
    }

    /**
     * Guardar nuevo producto (solo administrador)
     */
    public function store(StoreProductoRequest $request)
    {
        Log::channel('productos')->info('Producto creado', [
            'usuario_id' => auth()->id(),
            'nombre' => $request->nombre,
            'precio' => $request->precio,
        ]);

        $data = $request->validated();
        $data['usuario_id'] = auth()->id();

        $producto = Producto::create($data);
        $producto->categorias()->attach($request->categorias);

        return redirect()->route('productos.index')->with('success', 'Producto creado exitosamente');
    }

    /**
     * Mostrar formulario de edición (solo administrador)
     */
    public function edit(Producto $producto)
    {
        $this->authorize('update', $producto);
        $categorias = Categoria::all();
        return view('productos.edit', compact('producto', 'categorias'));
    }

    /**
     * Actualizar producto (solo administrador)
     */
    public function update(UpdateProductoRequest $request, Producto $producto)
    {
        Log::channel('productos')->info('Producto actualizado', [
            'usuario_id' => auth()->id(),
            'producto_id' => $producto->id,
            'nombre' => $request->nombre,
        ]);

        $producto->update($request->validated());
        $producto->categorias()->sync($request->categorias);

        return redirect()->route('productos.index')->with('success', 'Producto actualizado');
    }

    /**
     * Eliminar producto (solo administrador)
     */
    public function destroy(Producto $producto)
    {
        $this->authorize('delete', $producto);

        Log::channel('productos')->info('Producto eliminado', [
            'usuario_id' => auth()->id(),
            'producto_id' => $producto->id,
            'nombre' => $producto->nombre,
        ]);

        $producto->delete();

        return redirect()->route('productos.index')->with('success', 'Producto eliminado');
    }
}
