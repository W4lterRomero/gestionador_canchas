@extends('components.layouts.app')

@section('content')

<section class="container mx-auto mt-20 px-6">

    <h2 class="text-4xl font-extrabold text-center mb-6 text-white tracking-wide drop-shadow-lg">
        Equipo de Desarrollo
    </h2>

    <p class="text-center text-green-100 max-w-2xl mx-auto mb-16 text-lg leading-relaxed">
        Este proyecto ha sido construido por gente chambeadora y apasionada por la tecnología.
    </p>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-12">

        {{-- CARD TEMPLATE (repeated 4 times) --}}
        <div class="relative group bg-white rounded-3xl p-7 shadow-xl border border-green-400/40 
                    hover:-translate-y-3 transition-all duration-500">

            <div class="absolute inset-0 rounded-3xl bg-green-700/10 
                        opacity-0 group-hover:opacity-100 transition duration-500 pointer-events-none"></div>

            <div class="relative z-10 text-center">
                <img src="https://avatars.githubusercontent.com/ReyGerson?s=260&v=4"
                    class="w-28 h-28 mx-auto rounded-full object-cover shadow-lg mb-4
                           ring-4 ring-green-600/40 group-hover:ring-green-600 transition-all duration-500"
                    onerror="this.onerror=null;this.src='{{ asset('images/default-avatar.png') }}'">

                <h3 class="text-2xl font-bold text-green-700">Gerson Alegría</h3>
                <p class="text-gray-600 text-sm mb-4">@ReyGerson</p>

                <div class="flex justify-center gap-4 text-2xl">
                    <a href="https://github.com/ReyGerson" target="_blank" rel="noopener noreferrer"
                        class="text-gray-600 hover:text-green-700 transition-all duration-300 hover:scale-110">
                        <i class="fa-brands fa-github"></i>
                    </a>
                </div>
            </div>
        </div>

        <div class="relative group bg-white rounded-3xl p-7 shadow-xl border border-green-400/40 
                    hover:-translate-y-3 transition-all duration-500">

            <div class="absolute inset-0 rounded-3xl bg-green-700/10 
                        opacity-0 group-hover:opacity-100 transition duration-500 pointer-events-none"></div>

            <div class="relative z-10 text-center">
                <img src="https://avatars.githubusercontent.com/MrEkkito?s=260&v=4"
                    class="w-28 h-28 mx-auto rounded-full object-cover shadow-lg mb-4
                           ring-4 ring-green-600/40 group-hover:ring-green-600 transition-all duration-500"
                    onerror="this.onerror=null;this.src='{{ asset('images/default-avatar.png') }}'">

                <h3 class="text-2xl font-bold text-green-700">Pedro García</h3>
                <p class="text-gray-600 text-sm mb-4">@MrEkkito</p>

                <div class="flex justify-center gap-4 text-2xl">
                    <a href="https://github.com/MrEkkito" target="_blank" rel="noopener noreferrer"
                        class="text-gray-600 hover:text-green-700 transition-all duration-300 hover:scale-110">
                        <i class="fa-brands fa-github"></i>
                    </a>
                </div>
            </div>
        </div>

        <div class="relative group bg-white rounded-3xl p-7 shadow-xl border border-green-400/40 
                    hover:-translate-y-3 transition-all duration-500">

            <div class="absolute inset-0 rounded-3xl bg-green-700/10 
                        opacity-0 group-hover:opacity-100 transition duration-500 pointer-events-none"></div>

            <div class="relative z-10 text-center">
                <img src="{{ asset('images/img_David.jpeg') }}"
                    class="w-28 h-28 mx-auto rounded-full object-cover shadow-lg mb-4
                           ring-4 ring-green-600/40 group-hover:ring-green-600 transition-all duration-500">

                <h3 class="text-2xl font-bold text-green-700">David Romero</h3>
                <p class="text-gray-600 text-sm mb-4">@luxoritur</p>

                <div class="flex justify-center gap-4 text-2xl">
                    <a href="https://github.com/luxoritur" target="_blank" rel="noopener noreferrer"
                        class="text-gray-600 hover:text-green-700 transition-all duration-300 hover:scale-110">
                        <i class="fa-brands fa-github"></i>
                    </a>
                </div>
            </div>
        </div>

        <div class="relative group bg-white rounded-3xl p-7 shadow-xl border border-green-400/40 
                    hover:-translate-y-3 transition-all duration-500">

            <div class="absolute inset-0 rounded-3xl bg-green-700/10 
                        opacity-0 group-hover:opacity-100 transition duration-500 pointer-events-none"></div>

            <div class="relative z-10 text-center">
                <img src="https://avatars.githubusercontent.com/W4lterRomero?s=260&v=4"
                    class="w-28 h-28 mx-auto rounded-full object-cover shadow-lg mb-4
                           ring-4 ring-green-600/40 group-hover:ring-green-600 transition-all duration-500"
                    onerror="this.onerror=null;this.src='{{ asset('images/default-avatar.png') }}'">

                <h3 class="text-2xl font-bold text-green-700">Walter Romero</h3>
                <p class="text-gray-600 text-sm mb-4">@W4lterRomero</p>

                <div class="flex justify-center gap-4 text-2xl">
                    <a href="https://github.com/W4lterRomero" target="_blank" rel="noopener noreferrer"
                       class="text-gray-600 hover:text-green-700 transition-all duration-300 hover:scale-110">
                       <i class="fa-brands fa-github"></i>
                    </a>
                </div>
            </div>
        </div>

    </div>

</section>

@endsection
