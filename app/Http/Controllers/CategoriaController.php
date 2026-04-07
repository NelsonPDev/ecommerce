<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCategoriaRequest;
use App\Http\Requests\UpdateCategoriaRequest;
use App\Models\Categoria;

class CategoriaController extends Controller
{
    public function index()
    {
        $categorias = Categoria::withCount('productos')->latest()->paginate(10);

        return view('categorias.index', compact('categorias'));
    }

    public function create()
    {
        $this->authorize('create', Categoria::class);

        return view('categorias.create');
    }

    public function store(StoreCategoriaRequest $request)
    {
        Categoria::create($request->validated());

        return redirect()->route('categorias.index')->with('success', 'Categoria creada correctamente.');
    }

    public function show(Categoria $categoria)
    {
        $categoria->load('productos.usuario');

        return view('categorias.show', compact('categoria'));
    }

    public function edit(Categoria $categoria)
    {
        $this->authorize('update', $categoria);

        return view('categorias.edit', compact('categoria'));
    }

    public function update(UpdateCategoriaRequest $request, Categoria $categoria)
    {
        $categoria->update($request->validated());

        return redirect()->route('categorias.index')->with('success', 'Categoria actualizada correctamente.');
    }

    public function destroy(Categoria $categoria)
    {
        $this->authorize('delete', $categoria);

        $categoria->delete();

        return redirect()->route('categorias.index')->with('success', 'Categoria eliminada correctamente.');
    }
}
