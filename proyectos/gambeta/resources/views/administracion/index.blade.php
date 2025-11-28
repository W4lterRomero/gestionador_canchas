@extends('components.layouts.app')

@section('content')

<section class="container mx-auto px-6 py-10 space-y-12">

    <!-- =================================================================== -->
    <!-- 🔵 SECCIÓN: RESERVAS -->
    <!-- =================================================================== -->
    <div class="bg-white rounded-2xl shadow-xl p-8 border border-green-300">

        <h2 class="text-3xl font-bold text-green-700 mb-6 flex items-center gap-2">
            <i class="fa-solid fa-calendar-check text-green-500"></i>
            Gestión de Reservas
        </h2>

        <!-- FILTRO RÁPIDO -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">

            <input type="text" 
                class="mary-input mary-input-bordered bg-gray-50"
                placeholder="Buscar por cliente...">

            <select class="mary-select mary-select-bordered bg-gray-50">
                <option value="">Filtrar por estadio</option>
                <option>Barraza</option>
                <option>Charlaix</option>
                <option>Imbers</option>
            </select>

            <select class="mary-select mary-select-bordered bg-gray-50">
                <option value="">Estado</option>
                <option>Pendiente</option>
                <option>Confirmada</option>
                <option>Finalizada</option>
                <option>Cancelada</option>
            </select>

        </div>

        <!-- PRÓXIMAS RESERVAS -->
        <h3 class="text-2xl font-bold text-green-600 mb-3">Próximas Reservas</h3>

        <div class="space-y-4">

            <div class="bg-gray-50 p-4 rounded-xl border border-green-200 flex justify-between items-center">
                <div>
                    <p class="font-semibold text-gray-800">Carlos Pérez</p>
                    <p class="text-gray-600 text-sm">Estadio Barraza — 10:00 AM (Hoy)</p>
                </div>
                <span class="mary-badge mary-badge-warning">Pendiente</span>
            </div>

            <div class="bg-gray-50 p-4 rounded-xl border border-green-200 flex justify-between items-center">
                <div>
                    <p class="font-semibold text-gray-800">Andrea Gómez</p>
                    <p class="text-gray-600 text-sm">Estadio Correcaminos — 2:00 PM (Hoy)</p>
                </div>
                <span class="mary-badge mary-badge-success">Confirmada</span>
            </div>

        </div>

        <div class="flex justify-end mt-6">
            <a href="{{ route('reservas.index') }}"
               class="mary-btn bg-green-600 hover:bg-green-500 text-white rounded-full px-6">
                Ver todas las reservas
            </a>
        </div>

    </div>



    <!-- =================================================================== -->
    <!-- 🟢 SECCIÓN: ESTADIOS -->
    <!-- =================================================================== -->
    <div class="bg-white rounded-2xl shadow-xl p-8 border border-green-300">

        <h2 class="text-3xl font-bold text-green-700 mb-6 flex items-center gap-2">
            <i class="fa-solid fa-futbol text-green-500"></i>
            Gestión de Estadios
        </h2>

        <!-- ESTADIOS MÁS POPULARES -->
        <h3 class="text-2xl font-bold text-green-600 mb-3">Estadios más populares</h3>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">

            <div class="bg-gray-50 p-5 rounded-xl shadow border border-green-200">
                <h4 class="font-bold text-lg text-green-700">Estadio Barraza</h4>
                <p class="text-gray-600 text-sm">Reservas este mes: 32</p>
            </div>

            <div class="bg-gray-50 p-5 rounded-xl shadow border border-green-200">
                <h4 class="font-bold text-lg text-green-700">Estadio Charlaix</h4>
                <p class="text-gray-600 text-sm">Reservas este mes: 21</p>
            </div>

            <div class="bg-gray-50 p-5 rounded-xl shadow border border-green-200">
                <h4 class="font-bold text-lg text-green-700">Estadio Imbers</h4>
                <p class="text-gray-600 text-sm">Reservas este mes: 17</p>
            </div>

        </div>

        <div class="flex justify-end">
            <a href="{{ route('estadios.index') }}"
               class="mary-btn bg-green-600 hover:bg-green-500 text-white rounded-full px-6">
                Ver lista completa de estadios
            </a>
        </div>

    </div>




    <!-- =================================================================== -->
    <!-- 🟣 SECCIÓN: GESTIÓN DE USUARIOS -->
    <!-- =================================================================== -->
    <div class="bg-white rounded-2xl shadow-xl p-8 border border-green-300">

        <h2 class="text-3xl font-bold text-green-700 mb-6 flex items-center gap-2">
            <i class="fa-solid fa-users text-green-500"></i>
            Gestión de Usuarios
        </h2>

        <!-- RESUMEN DE USUARIOS -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">

            <div class="bg-gray-50 p-5 rounded-xl shadow border border-green-200 text-center">
                <h4 class="font-bold text-lg text-green-700">Total Usuarios</h4>
                <p class="text-3xl font-bold text-gray-800 mt-2">128</p>
            </div>

            <div class="bg-gray-50 p-5 rounded-xl shadow border border-green-200 text-center">
                <h4 class="font-bold text-lg text-green-700">Administradores</h4>
                <p class="text-3xl font-bold text-gray-800 mt-2">6</p>
            </div>

            <div class="bg-gray-50 p-5 rounded-xl shadow border border-green-200 text-center">
                <h4 class="font-bold text-lg text-green-700">Usuarios activos hoy</h4>
                <p class="text-3xl font-bold text-gray-800 mt-2">21</p>
            </div>

        </div>

        <!-- USUARIOS RECIENTES -->
        <h3 class="text-2xl font-bold text-green-600 mb-4">Usuarios recientes</h3>

        <div class="space-y-4">

            <div class="bg-gray-50 p-4 rounded-xl border border-green-200 flex justify-between items-center">
                <div>
                    <p class="font-semibold text-gray-800">José Martínez</p>
                    <p class="text-gray-600 text-sm">Registrado hace 2 días</p>
                </div>
                <span class="mary-badge mary-badge-primary">Activo</span>
            </div>

            <div class="bg-gray-50 p-4 rounded-xl border border-green-200 flex justify-between items-center">
                <div>
                    <p class="font-semibold text-gray-800">María López</p>
                    <p class="text-gray-600 text-sm">Registrada hace 5 días</p>
                </div>
                <span class="mary-badge mary-badge-warning">Pendiente</span>
            </div>

        </div>

        <div class="flex justify-end mt-6">
            <a href="{{ route('usuarios.index') }}"
               class="mary-btn bg-green-600 hover:bg-green-500 text-white rounded-full px-6">
                Ver todos los usuarios
            </a>
        </div>

    </div>

</section>

@endsection
