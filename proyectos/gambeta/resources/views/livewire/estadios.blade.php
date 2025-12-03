<div>

    <section class="container mx-auto pt-12 pb-6 px-6">

        <!-- Header Section -->
        <div class="mb-8">
            <h1 class="text-4xl font-bold text-white tracking-tight mb-2">Nuestras Canchas</h1>
            <p class="text-gray-200 text-lg">Encuentra la cancha perfecta para tu partido</p>
        </div>

        <!-- Filtros Container -->
        <div class="bg-white/80 backdrop-blur-xl rounded-3xl shadow-sm border border-gray-100 p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                <div class="relative group">
                    <div class="absolute inset-y-0 left-4 flex items-center pointer-events-none">
                        <svg class="w-5 h-5 text-gray-400 group-focus-within:text-green-600 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </div>
                    <input wire:model.live="search"
                        type="text"
                        class="w-full h-14 pl-12 pr-4 text-gray-900 placeholder-gray-400 bg-gray-50/50 border border-gray-200 rounded-2xl focus:outline-none focus:ring-2 focus:ring-green-500/20 focus:border-green-500 transition-all duration-200"
                        placeholder="Buscar cancha...">
                </div>

                <div class="relative group">
                    <div class="absolute inset-y-0 left-4 flex items-center pointer-events-none">
                        <svg class="w-5 h-5 text-gray-400 group-focus-within:text-green-600 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"/>
                        </svg>
                    </div>
                    <select wire:model.live="tipo"
                        class="w-full h-14 pl-12 pr-10 text-gray-900 bg-gray-50/50 border border-gray-200 rounded-2xl appearance-none focus:outline-none focus:ring-2 focus:ring-green-500/20 focus:border-green-500 transition-all duration-200 cursor-pointer">
                        <option value="">Todos los tipos</option>
                        <option>Fútbol 5</option>
                        <option>Fútbol rápido</option>
                        <option>Indoor</option>
                    </select>
                    <div class="absolute inset-y-0 right-4 flex items-center pointer-events-none">
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </div>
                </div>

            </div>

            <!-- Contador de resultados -->
            <div class="mt-4 flex items-center gap-2 text-sm">
                <div class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></div>
                <p class="text-gray-600 font-medium">
                    {{ $this->filtrados->count() }} {{ $this->filtrados->count() === 1 ? 'cancha encontrada' : 'canchas encontradas' }}
                </p>
            </div>
        </div>

    </section>


    <section class="container mx-auto px-6 pb-16">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">

        @forelse($this->filtrados as $cancha)

            <div class="group bg-white rounded-3xl overflow-hidden transition-all duration-300 hover:shadow-2xl hover:shadow-green-500/10 {{ !$cancha->activa ? 'opacity-60' : '' }} border border-gray-100">

                <!-- Imagen con overlay -->
                <div class="relative overflow-hidden aspect-[4/3]">
                    <img src="{{ $cancha->imagen_url ? asset($cancha->imagen_url) : asset('images/default-cancha.jpg') }}"
                         class="w-full h-full object-cover transform group-hover:scale-105 transition-transform duration-500"
                         alt="{{ $cancha->nombre }}">

                    <!-- Gradient overlay -->
                    <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>

                    <div class="absolute top-4 right-4">
                        @if($cancha->activa)
                            <span class="inline-flex items-center gap-1.5 px-4 py-2 rounded-full text-xs font-semibold bg-white/95 backdrop-blur-md text-green-700 shadow-lg ring-1 ring-green-600/20">
                                <span class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></span>
                                Disponible
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1.5 px-4 py-2 rounded-full text-xs font-semibold bg-white/95 backdrop-blur-md text-red-700 shadow-lg ring-1 ring-red-600/20">
                                <span class="w-2 h-2 bg-red-500 rounded-full"></span>
                                No disponible
                            </span>
                        @endif
                    </div>
                </div>

                <!-- Contenido -->
                <div class="p-6">
                    <h3 class="text-2xl font-bold text-gray-900 mb-2 group-hover:text-green-600 transition-colors">
                        {{ $cancha->nombre }}
                    </h3>

                    @if($cancha->descripcion)
                        <p class="text-sm text-gray-500 mb-4 line-clamp-2">
                            {{ $cancha->descripcion }}
                        </p>
                    @endif

                    <!-- Info Grid -->
                    <div class="space-y-3 mb-6">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-green-50 flex items-center justify-center flex-shrink-0">
                                <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                            </div>
                            <div class="flex-1">
                                <p class="text-xs text-gray-400 font-medium">Tipo</p>
                                <p class="text-sm font-semibold text-gray-900">{{ $cancha->tipo }}</p>
                            </div>
                        </div>

                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-green-50 flex items-center justify-center flex-shrink-0">
                                <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <div class="flex-1">
                                <p class="text-xs text-gray-400 font-medium">Precio por hora</p>
                                <p class="text-2xl font-bold text-green-600">${{ number_format($cancha->precio_hora, 2) }}</p>
                            </div>
                        </div>
                    </div>

                    <a href="{{ route('estadios.detalles', ['id' => $cancha->id]) }}"
                        class="group/btn relative w-full h-12 flex items-center justify-center rounded-2xl font-semibold text-sm overflow-hidden transition-all duration-300 {{ $cancha->activa ? 'bg-gradient-to-r from-green-600 to-green-500 hover:from-green-700 hover:to-green-600 text-white shadow-lg shadow-green-500/25 hover:shadow-xl hover:shadow-green-500/40' : 'bg-gray-100 text-gray-400 cursor-not-allowed' }}">
                        <span class="relative z-10 flex items-center gap-2">
                            Ver Detalles
                            @if($cancha->activa)
                                <svg class="w-4 h-4 transform group-hover/btn:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                                </svg>
                            @endif
                        </span>
                    </a>
                </div>

            </div>

        @empty
            <div class="col-span-full">
                <div class="flex flex-col items-center justify-center py-20 px-6">
                    <div class="w-20 h-20 bg-white/10 rounded-full flex items-center justify-center mb-6 backdrop-blur-sm">
                        <svg class="w-10 h-10 text-white/60" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-white mb-2">No se encontraron canchas</h3>
                    <p class="text-gray-300">Intenta ajustar los filtros de búsqueda</p>
                </div>
            </div>
        @endforelse

        </div>
    </section>

</div>
