<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CodigoVerificacion extends Model
{
    use HasFactory;

    protected $table = 'codigos_verificacion';

    protected $fillable = [
        'usuario_id',
        'codigo',
        'expiracion',
    ];

    protected $casts = [
        'expiracion' => 'datetime',
    ];

    public function usuario(): BelongsTo
    {
        return $this->belongsTo(Usuario::class);
    }
}
