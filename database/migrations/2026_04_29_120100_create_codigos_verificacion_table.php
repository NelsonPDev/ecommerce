<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('codigos_verificacion', function (Blueprint $table) {
            $table->id();
            $table->foreignId('usuario_id')->constrained('usuarios')->cascadeOnDelete();
            $table->string('codigo', 6);
            $table->timestamp('expiracion');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('codigos_verificacion');
    }
};
