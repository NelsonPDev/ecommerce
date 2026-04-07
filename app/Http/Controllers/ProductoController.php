<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProductoRequest;
use App\Http\Requests\UpdateProductoRequest;
use App\Models\Categoria;
use App\Models\Producto;
use Illuminate\Support\Facades\Log;

class ProductoController extends Controller
{
    public function index()
    {
        $productos = Producto::with(['usuario', 'categorias'])->latest()->paginate(12);

        return view('productos.index', compact('productos'));
    }

    public function show(Producto $producto)
    {
        $producto->load(['usuario', 'categorias']);

        return view('productos.show', compact('producto'));
    }

    public function create()
    {
        $this->authorize('create', Producto::class);

        $categorias = Categoria::orderBy('nombre')->get();

        return view('productos.create', compact('categorias'));
    }

    public function store(StoreProductoRequest $request)
    {
        $data = $request->validated();
        $producto = Producto::create([
            'nombre' => $data['nombre'],
            'descripcion' => $data['descripcion'],
            'precio' => $data['precio'],
            'existencia' => $data['existencia'],
            'usuario_id' => auth()->id(),
        ]);

        $producto->categorias()->sync($data['categorias']);

        Log::channel('productos')->info('Producto creado', [
            'usuario_id' => auth()->id(),
            'producto_id' => $producto->id,
            'nombre' => $producto->nombre,
        ]);

        return redirect()->route('productos.index')->with('success', 'Producto creado correctamente.');
    }

    public function edit(Producto $producto)
    {
        $this->authorize('update', $producto);

        $categorias = Categoria::orderBy('nombre')->get();
        $producto->load('categorias');

        return view('productos.edit', compact('producto', 'categorias'));
    }

    public function update(UpdateProductoRequest $request, Producto $producto)
    {
        $data = $request->validated();

        $producto->update([
            'nombre' => $data['nombre'],
            'descripcion' => $data['descripcion'],
            'precio' => $data['precio'],
            'existencia' => $data['existencia'],
        ]);

        $producto->categorias()->sync($data['categorias']);

        Log::channel('productos')->info('Producto actualizado', [
            'usuario_id' => auth()->id(),
            'producto_id' => $producto->id,
            'nombre' => $producto->nombre,
        ]);

        return redirect()->route('productos.index')->with('success', 'Producto actualizado correctamente.');
    }

    public function destroy(Producto $producto)
    {
        $this->authorize('delete', $producto);

        Log::channel('productos')->info('Producto eliminado', [
            'usuario_id' => auth()->id(),
            'producto_id' => $producto->id,
            'nombre' => $producto->nombre,
        ]);

        $producto->categorias()->detach();
        $producto->delete();

        return redirect()->route('productos.index')->with('success', 'Producto eliminado correctamente.');
    }
}
