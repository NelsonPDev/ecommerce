<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasMany as EloquentHasMany;
use Illuminate\Support\Facades\Storage;

class Producto extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre',
        'descripcion',
        'precio',
        'existencia',
        'fotos',
        'usuario_id',
    ];

    protected $casts = [
        'precio' => 'decimal:2',
        'fotos' => 'array',
    ];

    /**
     * Relación: Un producto pertenece a un usuario (vendedor)
     */
    public function usuario(): BelongsTo
    {
        return $this->belongsTo(Usuario::class);
    }

    /**
     * Relación: Un producto pertenece a muchas categorías (muchos a muchos)
     */
    public function categorias(): BelongsToMany
    {
        return $this->belongsToMany(Categoria::class, 'categoria_producto');
    }

    /**
     * Relación: Un producto tiene muchas ventas
     */
    public function ventas(): HasMany
    {
        return $this->hasMany(Venta::class);
    }

    public function categoriaProductos(): EloquentHasMany
    {
        return $this->hasMany(CategoriaProducto::class);
    }

    public function fotoUrls(): array
    {
        return collect($this->fotos ?? [])
            ->map(fn (string $path) => Storage::disk('public')->url($path))
            ->all();
    }

    public function primeraFotoUrl(): ?string
    {
        $foto = collect($this->fotos ?? [])->first();

        return $foto ? Storage::disk('public')->url($foto) : null;
    }
}
