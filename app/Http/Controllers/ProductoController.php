<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use App\Models\Categoria;
use App\Http\Requests\StoreProductoRequest;
use Illuminate\Support\Facades\Log;

class ProductoController extends Controller
{
    /**
     * Listar todos los productos (publicado)
     */
    public function index()
    {
        Log::info('Usuario ' . (auth()->user()?->email ?? 'anónimo') . ' visualizó catálogo de productos');

        $productos = Producto::with('categoria')->paginate(12);
        return view('productos.index', compact('productos'));
    }

    /**
     * Ver detalles de un producto
     */
    public function show(Producto $producto)
    {
        Log::info('Producto ' . $producto->nombre . ' consultado por ' . (auth()->user()?->email ?? 'anónimo'));
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
        Log::info('Intento de crear producto: ' . $request->nombre . ' por ' . auth()->user()->email);

        $producto = Producto::create($request->validated());

        Log::info('Producto creado: ' . $producto->nombre);

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
    public function update(\Illuminate\Http\Request $request, Producto $producto)
    {
        $this->authorize('update', $producto);

        Log::info('Intento de editar producto: ' . $producto->nombre . ' por ' . auth()->user()->email);

        $producto->update($request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'precio' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'categoria_id' => 'required|exists:categorias,id',
        ]));

        Log::info('Producto actualizado: ' . $producto->nombre);

        return redirect()->route('productos.index')->with('success', 'Producto actualizado');
    }

    /**
     * Eliminar producto (solo administrador)
     */
    public function destroy(Producto $producto)
    {
        $this->authorize('delete', $producto);

        Log::info('Producto ' . $producto->nombre . ' eliminado por ' . auth()->user()->email);

        $producto->delete();

        return redirect()->route('productos.index')->with('success', 'Producto eliminado');
    }
}
