<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Usuario extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'usuarios';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'nombre',
        'apellidos',
        'correo',
        'clave',
        'rol',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'clave',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'correo_verified_at' => 'datetime',
            'clave' => 'hashed',
        ];
    }

    /**
     * Get the name of the unique identifier for the user.
     */
    public function getAuthIdentifierName()
    {
        return 'correo';
    }

    /**
     * Get the password for the user.
     */
    public function getAuthPassword()
    {
        return $this->clave;
    }

    /**
     * Get the column name for the "remember me" token.
     */
    public function getRememberTokenName()
    {
        return 'remember_token';
    }

    /**
     * Relación: Un usuario tiene muchos productos (como vendedor)
     */
    public function productos(): HasMany
    {
        return $this->hasMany(Producto::class, 'usuario_id');
    }

    /**
     * Relación: Un usuario tiene muchas ventas como cliente
     */
    public function ventasComoCliente(): HasMany
    {
        return $this->hasMany(Venta::class, 'cliente_id');
    }

    /**
     * Relación: Un usuario tiene muchas ventas como vendedor
     */
    public function ventasComoVendedor(): HasMany
    {
        return $this->hasMany(Venta::class, 'vendedor_id');
    }

    /**
     * Relación hasManyThrough: Usuario -> Productos -> Categorías
     */
    public function categorias()
    {
        return $this->hasManyThrough(Categoria::class, Producto::class, 'usuario_id', 'id', 'id', 'categoria_id')
                    ->distinct();
    }
}
