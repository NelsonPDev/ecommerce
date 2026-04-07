<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Venta extends Model
{
    use HasFactory;

    protected $fillable = [
        'producto_id',
        'vendedor_id',
        'cliente_id',
        'fecha',
        'cantidad',
        'total',
    ];

    protected $casts = [
        'total' => 'decimal:2',
        'cantidad' => 'integer',
        'fecha' => 'date',
    ];

    /**
     * Relación: Una venta pertenece a un producto
     */
    public function producto(): BelongsTo
    {
        return $this->belongsTo(Producto::class);
    }

    /**
     * Relación: Una venta pertenece a un cliente (usuario)
     */
    public function cliente(): BelongsTo
    {
        return $this->belongsTo(Usuario::class, 'cliente_id');
    }

    /**
     * Relación: Una venta pertenece a un vendedor (usuario)
     */
    public function vendedor(): BelongsTo
    {
        return $this->belongsTo(Usuario::class, 'vendedor_id');
    }
}
