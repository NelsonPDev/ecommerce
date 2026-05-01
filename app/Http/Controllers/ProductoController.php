<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProductoRequest;
use App\Http\Requests\UpdateProductoRequest;
use App\Models\Categoria;
use App\Models\Producto;
use App\Models\Usuario;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

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
        $vendedores = Usuario::where('es_vendedor', true)->orderBy('nombre')->get();

        return view('productos.create', compact('categorias', 'vendedores'));
    }

    public function store(StoreProductoRequest $request)
    {
        $data = $request->validated();
        $usuario = auth()->user();
        $vendedor = $this->resolveVendedor($data);

        if (! $vendedor) {
            return back()->withErrors([
                'vendedor_id' => 'El vendedor indicado no existe o no puede asignarse a productos.',
            ])->withInput();
        }

        $producto = Producto::create([
            'nombre' => $data['nombre'],
            'descripcion' => $data['descripcion'],
            'precio' => $data['precio'],
            'existencia' => $data['existencia'],
            'fotos' => $this->storeFotos($request),
            'usuario_id' => $vendedor->id,
        ]);

        $producto->categorias()->sync($data['categorias']);

        Log::channel('productos')->info('Producto creado', [
            'usuario_id' => $usuario->id,
            'producto_id' => $producto->id,
            'nombre' => $producto->nombre,
        ]);

        return redirect()->route('productos.index')->with('success', 'Producto creado correctamente.');
    }

    public function edit(Producto $producto)
    {
        $this->authorize('update', $producto);

        $categorias = Categoria::orderBy('nombre')->get();
        $vendedores = Usuario::where('es_vendedor', true)->orderBy('nombre')->get();
        $producto->load(['categorias', 'usuario']);

        return view('productos.edit', compact('producto', 'categorias', 'vendedores'));
    }

    public function update(UpdateProductoRequest $request, Producto $producto)
    {
        $data = $request->validated();
        $usuario = auth()->user();
        $vendedor = $this->resolveVendedor($data);

        if (! $vendedor) {
            return back()->withErrors([
                'vendedor_id' => 'El vendedor indicado no existe o no puede asignarse a productos.',
            ])->withInput();
        }

        $producto->update([
            'nombre' => $data['nombre'],
            'descripcion' => $data['descripcion'],
            'precio' => $data['precio'],
            'existencia' => $data['existencia'],
            'fotos' => $request->hasFile('fotos') ? $this->replaceFotos($producto, $request) : $producto->fotos,
            'usuario_id' => $vendedor->id,
        ]);

        $producto->categorias()->sync($data['categorias']);

        Log::channel('productos')->info('Producto actualizado', [
            'usuario_id' => $usuario->id,
            'producto_id' => $producto->id,
            'nombre' => $producto->nombre,
        ]);

        return redirect()->route('productos.index')->with('success', 'Producto actualizado correctamente.');
    }

    public function destroy(Producto $producto)
    {
        $this->authorize('delete', $producto);
        $usuario = auth()->user();

        Log::channel('productos')->info('Producto eliminado', [
            'usuario_id' => $usuario->id,
            'producto_id' => $producto->id,
            'nombre' => $producto->nombre,
        ]);

        Storage::disk('public')->delete($producto->fotos ?? []);
        $producto->categorias()->detach();
        $producto->delete();

        return redirect()->route('productos.index')->with('success', 'Producto eliminado correctamente.');
    }

    protected function resolveVendedor(array $data): ?Usuario
    {
        if (!isset($data['vendedor_id'])) {
            return null;
        }

        $vendedor = Usuario::where('id', $data['vendedor_id'])
            ->where('es_vendedor', true)
            ->first();

        return $vendedor;
    }

    protected function storeFotos(StoreProductoRequest|UpdateProductoRequest $request): array
    {
        return collect($request->file('fotos', []))
            ->map(fn ($foto) => $foto->store('productos', 'public'))
            ->all();
    }

    protected function replaceFotos(Producto $producto, UpdateProductoRequest $request): array
    {
        Storage::disk('public')->delete($producto->fotos ?? []);

        return $this->storeFotos($request);
    }
}
