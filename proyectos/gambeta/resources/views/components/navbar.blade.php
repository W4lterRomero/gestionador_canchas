<nav class="navbar bg-main text-white shadow-lg px-6 py-4 flex justify-between items-center">

    <!-- LOGO -->
    <div class="flex items-center">
        <a href="{{ route('home') }}">
            <img src="{{ asset('images/logo.png') }}" alt="Logo" class="h-12 w-auto object-contain">
        </a>
    </div>

    <!-- MENU ESCRITORIO -->
    <ul class="hidden md:flex space-x-8 text-lg">
        <li><a href="{{ route('home') }}" class="hover:text-gray-300">Inicio</a></li>
        <li><a href="{{ route('estadios.index') }}" class="hover:text-gray-300">Estadios</a></li>
        <li><a href="{{ route('reservas.index') }}" class="hover:text-gray-300">Reservas</a></li>
        <li><a href="{{ route('reportes.index') }}" class="hover:text-gray-300">Reportes</a></li>
        <li><a href="{{ route('admin.index') }}" class="hover:text-gray-300">Administración</a></li>
    </ul>

    <!-- MENÚ RESPONSIVE -->
    <div class="dropdown dropdown-end md:hidden">
        <div tabindex="0" role="button" class="btn btn-blue text-white">Menú</div>
        <ul tabindex="0" class="dropdown-content menu p-2 shadow bg-base-100 rounded-box w-52">
            <li><a href="{{ route('home') }}">Inicio</a></li>
            <li><a href="{{ route('estadios.index') }}">Estadios</a></li>
            <li><a href="{{ route('reservas.index') }}">Reservas</a></li>
            <li><a href="{{ route('reportes.index') }}">Reportes</a></li>
            <li><a href="{{ route('admin.index') }}">Administración</a></li>
        </ul>
    </div>

    <!-- ICONO USUARIO -->
<div class="ml-6">
    <a href="{{ route('login') }}">
        <button class="btn btn-circle bg-blue-700 hover:bg-blue-900 text-white">
            <i class="fa-solid fa-user text-white text-2xl"></i>

        </button>
    </a>
</div>

</nav>
