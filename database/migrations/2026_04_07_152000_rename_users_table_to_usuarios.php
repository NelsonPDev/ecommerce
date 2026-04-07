<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Cambiar enum para incluir administrador
            $table->enum('role', ['cliente', 'empleado', 'gerente', 'administrador'])->change();
        });

        Schema::table('users', function (Blueprint $table) {
            $table->renameColumn('name', 'nombre');
            $table->renameColumn('email', 'correo');
            $table->renameColumn('password', 'clave');
            $table->renameColumn('role', 'rol');
            $table->string('apellidos')->nullable()->after('nombre');
            // Cambiar enum final
            $table->enum('rol', ['administrador', 'gerente', 'cliente'])->default('cliente')->change();
        });

        Schema::rename('users', 'usuarios');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::rename('usuarios', 'users');

        Schema::table('users', function (Blueprint $table) {
            $table->renameColumn('nombre', 'name');
            $table->renameColumn('correo', 'email');
            $table->renameColumn('clave', 'password');
            $table->renameColumn('rol', 'role');
            $table->dropColumn('apellidos');
        });
    }
};
