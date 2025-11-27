@extends('components.layouts.app')

@section('content')

    <!-- HEADER -->
    <header 
        class="text-center py-20 text-white shadow-xl bg-cover bg-center bg-no-repeat relative"
        style="background-image: url('{{ asset("images/Estadio.jpg") }}');"
    >

        <div class="absolute inset-0 bg-black/60"></div>

        <div class="relative z-10">
            <div class="flex justify-center mb-6">
                <img src="{{ asset('images/logo.png') }}" alt="Logo" class="h-40 w-auto object-contain drop-shadow-lg">
            </div>

            <h1 class="text-4xl font-bold mb-4">Sistema Nacional de Estadios Deportivos</h1>

            <p class="text-lg max-w-2xl mx-auto leading-relaxed">
                Plataforma diseñada para administrar estadios, coordinar reservaciones,
                gestionar horarios, controlar disponibilidad y centralizar
                la información operativa del deporte a nivel nacional.
            </p>
        </div>

    </header>

    <!-- CONTENIDO PRINCIPAL -->
    <section class="container mx-auto mt-12 grid grid-cols-1 md:grid-cols-3 gap-6">

        <div class="card card-dark shadow-xl border border-blue-800 p-6">
            <h2 class="text-xl font-bold text-blue-400">Gestión de Estadios</h2>
            <p>Administración completa de estadios y centros deportivos.</p>
            <a href="{{ route('estadios.index') }}" class="btn btn-blue text-white mt-3">Entrar</a>
        </div>

        <div class="card card-dark shadow-xl border border-blue-800 p-6">
            <h2 class="text-xl font-bold text-blue-400">Reservaciones</h2>
            <p>Control de horarios, disponibilidad y solicitudes.</p>
            <a href="{{ route('reservas.index') }}" class="btn btn-blue text-white mt-3">Entrar</a>
        </div>

        <div class="card card-dark shadow-xl border border-blue-800 p-6">
            <h2 class="text-xl font-bold text-blue-400">Reportes</h2>
            <p>Información detallada de uso, actividades y programación.</p>
            <a href="{{ route('reportes.index') }}" class="btn btn-blue text-white mt-3">Entrar</a>
        </div>

    </section>

@endsection