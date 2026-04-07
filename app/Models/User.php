<?php

namespace App\Models;

use Database\Factories\UsuarioFactory;

class User extends Usuario
{
    protected static function newFactory()
    {
        return UsuarioFactory::new();
    }
}
