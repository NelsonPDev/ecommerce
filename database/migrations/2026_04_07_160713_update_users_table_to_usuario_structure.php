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
            // Renombrar campos
            $table->renameColumn('name', 'nombre');
            $table->renameColumn('email', 'correo');
            $table->renameColumn('email_verified_at', 'correo_verified_at');
            $table->renameColumn('password', 'clave');

            // Agregar campo apellidos
            $table->string('apellidos')->nullable()->after('nombre');

            // Renombrar columna role a rol y actualizar enum
            $table->renameColumn('role', 'rol');
            $table->enum('rol', ['administrador', 'gerente', 'cliente'])->default('cliente')->change();
        });

        // Actualizar tabla password_reset_tokens
        Schema::table('password_reset_tokens', function (Blueprint $table) {
            $table->renameColumn('email', 'correo');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Revertir cambios
            $table->renameColumn('nombre', 'name');
            $table->renameColumn('correo', 'email');
            $table->renameColumn('correo_verified_at', 'email_verified_at');
            $table->renameColumn('clave', 'password');
            $table->dropColumn('apellidos');
            $table->renameColumn('rol', 'role');
            $table->enum('role', ['cliente', 'empleado', 'gerente'])->default('cliente')->change();
        });

        Schema::table('password_reset_tokens', function (Blueprint $table) {
            $table->renameColumn('correo', 'email');
        });
    }
};
