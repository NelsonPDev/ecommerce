<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Categoria extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre',
        'descripcion',
    ];

    /**
     * Relación: Una categoría pertenece a muchos productos (muchos a muchos)
     */
    public function productos(): BelongsToMany
    {
        return $this->belongsToMany(Producto::class, 'categoria_producto');
    }
}
