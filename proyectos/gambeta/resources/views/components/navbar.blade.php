<nav class="navbar px-6 py-4 shadow-xl bg-green-700 backdrop-blur-xl border-b border-green-500/40">

    <!-- LOGO -->
    <div class="flex items-center gap-3">
        <a href="{{ route('home') }}" class="flex items-center">
            <img src="{{ asset('images/logo.png') }}" 
                 alt="Logo" 
                 class="h-12 w-auto object-contain drop-shadow-md hover:scale-105 transition">
        </a>
    </div>

    <!-- MENÚ ESCRITORIO -->
    <ul class="hidden md:flex space-x-8 text-lg font-medium text-white">

        <li>
            <a href="{{ route('home') }}" 
               class="hover:text-green-300 hover:underline decoration-green-300 underline-offset-8 transition">
                Inicio
            </a>
        </li>

        <li>
            <a href="{{ route('estadios.index') }}" 
               class="hover:text-green-300 hover:underline decoration-green-300 underline-offset-8 transition">
                Estadios
            </a>
        </li>

        <li>
            <a href="{{ route('reservas.index') }}" 
               class="hover:text-green-300 hover:underline decoration-green-300 underline-offset-8 transition">
                Reservas
            </a>
        </li>

        <li>
            <a href="{{ route('admin.index') }}" 
               class="hover:text-green-300 hover:underline decoration-green-300 underline-offset-8 transition">
                Administración
            </a>
        </li>

    </ul>

    <!-- MENÚ RESPONSIVE -->
    <div class="md:hidden">
        <div class="dropdown dropdown-end">
            <label tabindex="0" class="mary-btn mary-btn-sm shadow-md bg-green-600 border-none hover:bg-green-500 text-white">
                <i class="fa-solid fa-bars text-xl"></i>
            </label>

            <ul tabindex="0" 
                class="dropdown-content menu p-4 mt-3 shadow-xl bg-green-700 text-white rounded-xl w-52 z-[9999]">

                <li><a href="{{ route('home') }}" class="hover:bg-green-500 rounded-lg">Inicio</a></li>
                <li><a href="{{ route('estadios.index') }}" class="hover:bg-green-500 rounded-lg">Estadios</a></li>
                <li><a href="{{ route('reservas.index') }}" class="hover:bg-green-500 rounded-lg">Reservas</a></li>
                <li><a href="{{ route('admin.index') }}" class="hover:bg-green-500 rounded-lg">Administración</a></li>

            </ul>
        </div>
    </div>

    <!-- ICONO USUARIO -->
    <div class="ml-6 hidden md:block">
        <a href="{{ route('login') }}">
            <button class="mary-btn mary-btn-circle shadow-lg bg-green-600 border-none hover:bg-green-500 hover:scale-110 transition">
                <i class="fa-solid fa-user text-white text-xl"></i>
            </button>
        </a>
    </div>

</nav>
