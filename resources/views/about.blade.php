@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-100">
    <!-- Header -->
    <div class="bg-white shadow">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
            <h1 class="text-4xl font-bold text-gray-900">Quiénes Somos</h1>
        </div>
    </div>

    <!-- Contenido -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
        <div class="bg-white rounded-lg shadow p-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-4">Sobre TechStore</h2>
            <p class="text-gray-600 mb-4">
                TechStore es una tienda online líder en la venta de productos tecnológicos de alta calidad. 
                Con más de 10 años de experiencia en el mercado, nos hemos posicionado como una de las opciones 
                más confiables para nuestros clientes.
            </p>

            <h2 class="text-2xl font-bold text-gray-900 mb-4 mt-8">Misión</h2>
            <p class="text-gray-600 mb-4">
                Proporcionar productos tecnológicos de calidad a precios competitivos, brindando una experiencia 
                de compra segura y confiable, con un servicio al cliente excepcional.
            </p>

            <h2 class="text-2xl font-bold text-gray-900 mb-4 mt-8">Visión</h2>
            <p class="text-gray-600 mb-4">
                Ser la plataforma de e-commerce más confiable en la región, ofreciendo a nuestros clientes 
                la mejor selección de productos tecnológicos con garantía de autenticidad y calidad.
            </p>

            <h2 class="text-2xl font-bold text-gray-900 mb-4 mt-8">Nuestros Valores</h2>
            <ul class="list-disc list-inside text-gray-600 space-y-2">
                <li>Honestidad y transparencia en nuestras operaciones</li>
                <li>Compromiso con la calidad de nuestros productos y servicios</li>
                <li>Atención al cliente como prioridad principal</li>
                <li>Innovación continua en nuestras ofertas</li>
                <li>Responsabilidad social y ambiental</li>
            </ul>
        </div>
    </div>
</div>
@endsection
