<nav class="navbar fixed top-0 left-0 w-full z-50
            px-6 py-4 shadow-xl 
            bg-gradient-to-r from-[#022019] via-[#063b2b] to-[#0b5c41]
            backdrop-blur-xl border-b border-green-500/20">
    <div class="flex items-center gap-3">
        <a href="{{ auth()->check() ? route('inicio') : route('login') }}" class="flex items-center">
            <img src="{{ asset('images/logo.png') }}" 
                 alt="Logo" 
                 class="h-12 w-auto object-contain drop-shadow-md hover:scale-105 transition">
        </a>
    </div>

    @auth
    <ul class="hidden md:flex space-x-8 text-lg font-medium text-white">

        <li>
            <a href="{{ route('inicio') }}" 
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

        @if(auth()->user()->isAdmin())
        <li>
            <a href="{{ route('admin.index') }}" 
               class="hover:text-green-300 hover:underline decoration-green-300 underline-offset-8 transition">
                Administración
            </a>
        </li>
        @endif

    </ul>

    <div class="md:hidden">
        <div class="dropdown dropdown-end">
            <label tabindex="0" 
                class="mary-btn mary-btn-sm shadow-md 
                       bg-[#0b5c41] hover:bg-[#167a52] border-none text-white transition">
                <i class="fa-solid fa-bars text-xl"></i>
            </label>

            <ul tabindex="0" 
                class="dropdown-content menu p-4 mt-3 shadow-xl
                       bg-[#063b2b] text-white rounded-xl w-52 z-[9999]">

                <li><a href="{{ route('inicio') }}" class="hover:bg-[#0b5c41] rounded-lg">Inicio</a></li>
                <li><a href="{{ route('estadios.index') }}" class="hover:bg-[#0b5c41] rounded-lg">Estadios</a></li>
                <li><a href="{{ route('reservas.index') }}" class="hover:bg-[#0b5c41] rounded-lg">Reservas</a></li>

                @if(auth()->user()->isAdmin())
                <li><a href="{{ route('admin.index') }}" class="hover:bg-[#0b5c41] rounded-lg">Administración</a></li>
                @endif

            </ul>
        </div>
    </div>

    <div class="ml-6 hidden md:flex items-center gap-3">
        <span class="text-white text-sm">{{ auth()->user()->name }}</span>
        <form action="{{ route('admin.logout') }}" method="POST" class="inline">
            @csrf
            <button type="submit" 
                class="mary-btn mary-btn-circle shadow-lg 
                    bg-[#0b5c41] border-none hover:bg-[#167a52] hover:scale-110 transition"
                title="Cerrar sesión">
                <i class="fa-solid fa-sign-out-alt text-white text-xl"></i>
            </button>
        </form>
    </div>
    @endauth

    @guest
    <div class="ml-6 hidden md:block">
        <a href="{{ route('login') }}">
            <button class="mary-btn mary-btn-circle shadow-lg
                           bg-[#0b5c41] border-none hover:bg-[#167a52] hover:scale-110 transition">
                <i class="fa-solid fa-user text-white text-xl"></i>
            </button>
        </a>
    </div>
    @endguest

</nav>
