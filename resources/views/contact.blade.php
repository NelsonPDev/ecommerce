@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-100">
    <!-- Header -->
    <div class="bg-white shadow">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
            <h1 class="text-4xl font-bold text-gray-900">Contáctanos</h1>
        </div>
    </div>

    <!-- Contenido -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <!-- Información de Contacto -->
            <div class="bg-white rounded-lg shadow p-8">
                <h2 class="text-2xl font-bold text-gray-900 mb-6">Información de Contacto</h2>
                
                <div class="mb-6">
                    <h3 class="text-lg font-bold text-gray-900">Dirección</h3>
                    <p class="text-gray-600">123 Calle Principal, Ciudad, País</p>
                </div>

                <div class="mb-6">
                    <h3 class="text-lg font-bold text-gray-900">Teléfono</h3>
                    <p class="text-gray-600">+1 (555) 123-4567</p>
                </div>

                <div class="mb-6">
                    <h3 class="text-lg font-bold text-gray-900">Email</h3>
                    <p class="text-gray-600">info@techstore.com</p>
                </div>

                <div class="mb-6">
                    <h3 class="text-lg font-bold text-gray-900">Horario de Atención</h3>
                    <p class="text-gray-600">Lunes a Viernes: 9:00 AM - 6:00 PM</p>
                    <p class="text-gray-600">Sábado: 10:00 AM - 4:00 PM</p>
                    <p class="text-gray-600">Domingo: Cerrado</p>
                </div>
            </div>

            <!-- Formulario de Contacto -->
            <div class="bg-white rounded-lg shadow p-8">
                <h2 class="text-2xl font-bold text-gray-900 mb-6">Envíanos tu Mensaje</h2>
                
                <form action="#" method="POST" class="space-y-4">
                    @csrf
                    
                    <div>
                        <label class="block text-gray-700 font-bold mb-2">Nombre</label>
                        <input type="text" name="name" class="w-full px-4 py-2 border border-gray-300 rounded" required>
                    </div>

                    <div>
                        <label class="block text-gray-700 font-bold mb-2">Email</label>
                        <input type="email" name="email" class="w-full px-4 py-2 border border-gray-300 rounded" required>
                    </div>

                    <div>
                        <label class="block text-gray-700 font-bold mb-2">Asunto</label>
                        <input type="text" name="subject" class="w-full px-4 py-2 border border-gray-300 rounded" required>
                    </div>

                    <div>
                        <label class="block text-gray-700 font-bold mb-2">Mensaje</label>
                        <textarea name="message" rows="5" class="w-full px-4 py-2 border border-gray-300 rounded" required></textarea>
                    </div>

                    <button type="submit" class="w-full bg-blue-600 text-white font-bold py-2 px-4 rounded hover:bg-blue-700">
                        Enviar Mensaje
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
