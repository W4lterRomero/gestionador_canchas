@extends('components.layouts.app')

@section('content')

<!-- HERO / HEAD SECTION -->
<header
    class="relative h-[480px] bg-cover bg-center bg-no-repeat flex items-center justify-center text-white"
    style="background-image: url('{{ asset("images/EstadioMagico.png") }}');"
>
    <!-- Overlay suave color verde -->
    <div class="absolute inset-0 bg-gradient-to-b from-green-800/30 via-green-700/20 to-green-900/30"></div>

    <!-- Contenido -->
    <div class="relative z-10 text-center px-6">
        <img src="{{ asset('images/logo.png') }}"
             class="h-32 w-auto mx-auto mb-4 drop-shadow-xl" alt="Logo">

        <h1 class="text-4xl md:text-5xl font-bold mb-3 tracking-wide">
            Sistema Nacional de Estadios Deportivos
        </h1>

        <p class="max-w-2xl mx-auto text-lg opacity-95">
            Plataforma moderna para administrar estadios, coordinar reservaciones y gestionar
            la disponibilidad deportiva a nivel nacional.
        </p>
    </div>
</header>

<!-- OPCIONES PRINCIPALES -->
<section class="container mx-auto mt-14 px-6 grid grid-cols-1 md:grid-cols-3 gap-8">

    <!-- CARD 1 -->
    <div class="bg-green-700/90 text-white p-7 shadow-lg rounded-3xl border border-green-400/30
                hover:shadow-2xl hover:-translate-y-1 transition-all duration-300">

        <h2 class="text-2xl font-semibold mb-2">Gestión de Estadios</h2>

        <p class="text-sm mt-2 opacity-95 leading-relaxed">
            Administración completa de instalaciones deportivas con herramientas actualizadas.
        </p>

        <div class="flex justify-center mt-6">
            <a href="{{ route('estadios.index') }}"
               class="mary-btn rounded-full px-8 py-2 bg-white text-green-700 font-semibold
                      hover:bg-green-100 shadow-md border-none">
                Entrar
            </a>
        </div>
    </div>


    <!-- CARD 2 -->
    <div class="bg-green-600/90 text-white p-7 shadow-lg rounded-3xl border border-green-300/30
                hover:shadow-2xl hover:-translate-y-1 transition-all duration-300">

        <h2 class="text-2xl font-semibold mb-2">Reservaciones</h2>

        <p class="text-sm mt-2 opacity-95 leading-relaxed">
            Control de horarios, disponibilidad en tiempo real y solicitudes de uso.
        </p>

        <div class="flex justify-center mt-6">
            <a href="{{ route('reservas.index') }}"
               class="mary-btn rounded-full px-8 py-2 bg-white text-green-700 font-semibold
                      hover:bg-green-100 shadow-md border-none">
                Entrar
            </a>
        </div>
    </div>


    <!-- CARD 3 -->
    <div class="bg-green-500/90 text-white p-7 shadow-lg rounded-3xl border border-green-200/30
                hover:shadow-2xl hover:-translate-y-1 transition-all duration-300">

        <h2 class="text-2xl font-semibold mb-2">Reportes</h2>

        <p class="text-sm mt-2 opacity-95 leading-relaxed">
            Información detallada sobre actividad deportiva y uso de las instalaciones.
        </p>

    </div>

</section>

<!-- SECCIÓN PROMOCIONAL -->
<section class="container mx-auto mt-14 px-6">

    <h2 class="text-3xl font-bold text-green-700 text-center mb-10 tracking-wide">
        Algunos de nuestros estadios
    </h2>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-10">

        <!-- Estadio 1 -->
        <div class="rounded-3xl overflow-hidden shadow-lg hover:shadow-2xl hover:-translate-y-1 transition-all duration-300 bg-white">
            <img src="{{ asset('images/EstadioMagico.png') }}"
                 class="h-56 w-full object-cover" alt="Estadio Mágico González">
            <div class="p-5 text-center">
                <h3 class="text-xl font-semibold text-green-700">Estadio Mágico González</h3>
                <p class="text-sm text-gray-600 mt-2">
                    Ubicado en San Salvador, uno de los recintos deportivos más importantes del país.
                </p>
            </div>
        </div>

        <!-- Estadio 2 -->
        <div class="rounded-3xl overflow-hidden shadow-lg hover:shadow-2xl hover:-translate-y-1 transition-all duration-300 bg-white">
            <img src="{{ asset('images/EstadioBarraza.jpg') }}"
                 class="h-56 w-full object-cover" alt="Estadio Juan Francisco Barraza">
            <div class="p-5 text-center">
                <h3 class="text-xl font-semibold text-green-700">Estadio Juan Francisco Barraza</h3>
                <p class="text-sm text-gray-600 mt-2">
                    Ícono deportivo de la zona oriental, ubicado en San Miguel.
                </p>
            </div>
        </div>

        <!-- Estadio 3 -->
        <div class="rounded-3xl overflow-hidden shadow-lg hover:shadow-2xl hover:-translate-y-1 transition-all duration-300 bg-white">
            <img src="{{ asset('images/EstadioFlores.jpg') }}"
                 class="h-56 w-full object-cover" alt="Estadio Ramón Flores Berríos">
            <div class="p-5 text-center">
                <h3 class="text-xl font-semibold text-green-700">Estadio Ramón Flores Berríos</h3>
                <p class="text-sm text-gray-600 mt-2">
                    Sede del fútbol limeño, muy reconocido en Santa Rosa de Lima.
                </p>
            </div>
        </div>

    </div>

</section>

   @endsection
