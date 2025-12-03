@extends('components.layouts.app')

@section('content')

<header
    class="relative h-[420px] md:h-[480px] bg-cover bg-center bg-no-repeat flex items-center justify-center text-white"
    style="background-image: url('{{ asset("images/EstadioMagico.png") }}');"
>
    <div class="absolute inset-0 bg-gradient-to-b 
                from-[#063b2b]/70 via-[#0b5c41]/60 to-[#167a52]/70"></div>

    <div class="relative z-10 text-center px-6 max-w-3xl mx-auto">
        <img src="{{ asset('images/logo.png') }}"
             class="h-28 md:h-32 w-auto mx-auto mb-4 drop-shadow-xl" alt="Logo">

        <h1 class="text-3xl md:text-5xl font-bold mb-3 tracking-wide">
            Sistema Nacional de Estadios Deportivos
        </h1>

        <p class="text-md md:text-lg opacity-95">
            Plataforma moderna para administrar estadios, coordinar reservaciones y gestionar
            la disponibilidad deportiva a nivel nacional.
        </p>
    </div>
</header>

{{-- Sección Información Inicial --}}
<section class="container mx-auto mt-16 px-6">

    <div class="max-w-3xl mx-auto text-center">

        <h2 class="text-3xl font-bold text-white mb-6 tracking-wide">
            ¿Qué es el Sistema Nacional de Estadios Deportivos?
        </h2>

        <p class="text-gray-200 leading-relaxed text-lg">
            Es una plataforma moderna diseñada para centralizar la gestión, disponibilidad y reservación 
            de las principales instalaciones deportivas de El Salvador. Permite a los usuarios consultar 
            horarios, reservar canchas y acceder a información actualizada sobre estadios emblemáticos 
            del país.
        </p>

    </div>

</section>

{{-- Sección Opciones --}}
<section class="container mx-auto mt-14 px-6">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 place-items-center">

        {{-- Gestión de Estadios --}}
        <div class="w-full max-w-sm bg-white text-green-700 p-7 shadow-lg rounded-3xl border border-green-400/40
                    hover:shadow-2xl hover:-translate-y-1 transition-all duration-300">

            <h2 class="text-2xl font-semibold mb-2 text-center">Gestión de Estadios</h2>

            <p class="text-sm mt-2 opacity-90 leading-relaxed text-center">
                Administración completa de instalaciones deportivas con herramientas actualizadas.
            </p>

            <div class="flex justify-center mt-6">
                <a href="{{ route('estadios.index') }}"
                   class="rounded-full px-8 py-2 font-semibold shadow-md border border-green-700
                          bg-green-700 text-white hover:bg-green-800 transition">
                    Entrar
                </a>
            </div>
        </div>

        {{-- Reservaciones --}}
        <div class="w-full max-w-sm bg-white text-green-700 p-7 shadow-lg rounded-3xl border border-green-400/40
                    hover:shadow-2xl hover:-translate-y-1 transition-all duration-300">

            <h2 class="text-2xl font-semibold mb-2 text-center">Reservaciones</h2>

            <p class="text-sm mt-2 opacity-90 leading-relaxed text-center">
                Control de horarios, disponibilidad en tiempo real y solicitudes de uso.
            </p>

            <div class="flex justify-center mt-6">
                <a href="{{ route('reservas.index') }}"
                   class="rounded-full px-8 py-2 font-semibold shadow-md border border-green-700
                          bg-green-700 text-white hover:bg-green-800 transition">
                    Entrar
                </a>
            </div>
        </div>

    </div>
</section>

{{-- Sección Estadios --}}
<section class="container mx-auto mt-16 px-6">

    <h2 class="text-3xl font-bold text-center mb-12 tracking-wide text-white">
        Algunos de nuestros estadios
    </h2>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-10 place-items-center">

        {{-- Estadio 1 --}}
        <div class="w-full max-w-sm bg-white rounded-3xl overflow-hidden shadow-lg hover:shadow-2xl
                    hover:-translate-y-1 transition-all duration-300 border border-green-300">

            <img src="{{ asset('images/EstadioMagico.png') }}"
                 class="h-56 w-full object-cover" alt="Estadio Mágico González">

            <div class="p-5 text-center">
                <h3 class="text-xl font-semibold text-green-700">Estadio Mágico González</h3>
                <p class="text-sm text-gray-600 mt-2">
                    Ubicado en San Salvador, uno de los recintos deportivos más importantes del país.
                </p>
            </div>
        </div>

        {{-- Estadio 2 --}}
        <div class="w-full max-w-sm bg-white rounded-3xl overflow-hidden shadow-lg hover:shadow-2xl
                    hover:-translate-y-1 transition-all duration-300 border border-green-300">

            <img src="{{ asset('images/EstadioBarraza.jpg') }}"
                 class="h-56 w-full object-cover" alt="Estadio Juan Francisco Barraza">

            <div class="p-5 text-center">
                <h3 class="text-xl font-semibold text-green-700">Estadio Juan Francisco Barraza</h3>
                <p class="text-sm text-gray-600 mt-2">
                    Ícono deportivo de la zona oriental, ubicado en San Miguel.
                </p>
            </div>
        </div>

        {{-- Estadio 3 --}}
        <div class="w-full max-w-sm bg-white rounded-3xl overflow-hidden shadow-lg hover:shadow-2xl
                    hover:-translate-y-1 transition-all duration-300 border border-green-300">

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

{{-- Sección Historia Estadios --}}
<section class="container mx-auto mt-20 px-6">

    <h2 class="text-3xl font-bold text-center mb-12 text-white tracking-wide">
        Historia de algunos estadios emblemáticos
    </h2>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-12 max-w-6xl mx-auto">

        {{-- Estadio Mágico González --}}
        <div class="px-4">
            <h3 class="text-2xl font-semibold text-white mb-3 text-center">
                Estadio Mágico González
            </h3>
            <p class="text-gray-200 leading-relaxed text-lg text-justify">
                Fundado en 1935, este estadio es considerado el recinto deportivo más importante del país.
                Lleva el nombre del histórico Jorge “El Mágico” González, un referente mundial por su talento
                excepcional y uno de los futbolistas salvadoreños más influyentes. Su legado ha convertido
                este estadio en un símbolo de identidad para el deporte nacional.
            </p>
        </div>

        {{-- Estadio Juan Francisco Barraza --}}
        <div class="px-4">
            <h3 class="text-2xl font-semibold text-white mb-3 text-center">
                Estadio Juan Francisco Barraza
            </h3>
            <p class="text-gray-200 leading-relaxed text-lg text-justify">
                Inaugurado en 1959 en San Miguel, es el hogar del Club Deportivo Águila. Reconocido por la
                energía de su afición y su importancia histórica en la región oriental, ha sido escenario de
                innumerables encuentros memorables y un referente del fútbol salvadoreño durante décadas.
            </p>
        </div>

        {{-- Estadio Ramón Flores Berríos --}}
        <div class="px-4">
            <h3 class="text-2xl font-semibold text-white mb-3 text-center">
                Estadio Ramón Flores Berríos
            </h3>
            <p class="text-gray-200 leading-relaxed text-lg text-justify">
                Desde 1974 ha sido la sede principal del Municipal Limeño. Este recinto representa la tradición
                deportiva de Santa Rosa de Lima y es un punto clave para la comunidad. Su historia está marcada
                por la pasión de sus aficionados y su rol fundamental en la identidad futbolística de la zona norte.
            </p>
        </div>

    </div>

</section>



@endsection
