<div class="min-h-screen">

    {{-- Hero --}}
    <div class="relative h-96 overflow-hidden group">
        <img src="{{ $this->cancha->imagen_url ? asset($this->cancha->imagen_url) : asset('images/default-cancha.jpg') }}"
             class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105"
             alt="{{ $this->cancha->nombre }}">

        <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/40 to-transparent transition-opacity duration-300 group-hover:from-black/70"></div>

        {{-- Contenido --}}
        <div class="absolute inset-0 flex items-end">
            <div class="container mx-auto px-6 pb-12">

                <div class="mb-4 transform transition-all duration-300 hover:scale-105">
                    @if($this->cancha->activa)
                        <span class="inline-flex items-center gap-2 px-4 py-2 rounded-full text-sm font-semibold bg-white/95 backdrop-blur-md text-green-700 shadow-lg ring-1 ring-green-600/20 hover:bg-white hover:shadow-xl hover:ring-2 transition-all cursor-default">
                            <span class="w-2.5 h-2.5 bg-green-500 rounded-full animate-pulse"></span>
                            Disponible para reservar
                        </span>
                    @else
                        <span class="inline-flex items-center gap-2 px-4 py-2 rounded-full text-sm font-semibold bg-white/95 backdrop-blur-md text-red-700 shadow-lg ring-1 ring-red-600/20 hover:bg-white hover:shadow-xl hover:ring-2 transition-all cursor-default">
                            <span class="w-2.5 h-2.5 bg-red-500 rounded-full"></span>
                            No disponible
                        </span>
                    @endif
                </div>

                {{-- Título --}}
                <h1 class="text-5xl font-bold text-white mb-3 tracking-tight transform transition-all duration-300 hover:translate-x-2">{{ $this->cancha->nombre }}</h1>
                <p class="text-xl text-gray-200 transition-colors duration-300 hover:text-white">{{ $this->cancha->tipo }}</p>
            </div>
        </div>

        <a href="{{ route('estadios.index') }}"
           class="absolute top-6 left-6 w-12 h-12 rounded-full bg-white/90 backdrop-blur-md shadow-lg flex items-center justify-center hover:bg-white hover:shadow-2xl hover:scale-110 transition-all duration-300 group/back">
            <svg class="w-6 h-6 text-gray-900 transition-transform duration-300 group-hover/back:-translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
        </a>
    </div>

    {{-- Principal --}}
    <div class="container mx-auto px-6 py-12">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

            <div class="lg:col-span-2 space-y-6">

                @if($this->cancha->descripcion)
                <div class="bg-white rounded-3xl p-8 shadow-sm border border-gray-100 hover:shadow-xl hover:border-green-100 transition-all duration-300 group">
                    <h2 class="text-2xl font-bold text-gray-900 mb-4 group-hover:text-green-600 transition-colors">Acerca de esta cancha</h2>
                    <p class="text-gray-600 leading-relaxed group-hover:text-gray-700 transition-colors">{{ $this->cancha->descripcion }}</p>

                    {{-- Galería --}}
                    <div class="mt-8">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-semibold text-gray-900">Galería de fotos</h3>
                        </div>

                        @if($this->cancha->imagenes->count() > 0)
                            <div class="grid grid-cols-2 md:grid-cols-3 gap-4" x-data="{ 
                                open: false, 
                                currentIndex: 0,
                                images: [
                                    @foreach($this->cancha->imagenes as $imagen)
                                        '{{ asset($imagen->imagen_url) }}',
                                    @endforeach
                                ],
                                get currentImage() {
                                    return this.images[this.currentIndex];
                                },
                                next() {
                                    this.currentIndex = (this.currentIndex + 1) % this.images.length;
                                },
                                prev() {
                                    this.currentIndex = (this.currentIndex - 1 + this.images.length) % this.images.length;
                                }
                            }"
                            @keydown.escape.window="open = false"
                            @keydown.arrow-right.window="next()"
                            @keydown.arrow-left.window="prev()">
                            
                                @foreach($this->cancha->imagenes as $index => $imagen)
                                    <div class="relative group/img overflow-hidden rounded-xl cursor-pointer aspect-video shadow-sm hover:shadow-md transition-all">
                                        <img src="{{ asset($imagen->imagen_url) }}" 
                                             class="w-full h-full object-cover transform transition-transform duration-500 group-hover/img:scale-110" 
                                             alt="Galería"
                                             @click="open = true; currentIndex = {{ $index }}">
                                        
                                        <div class="absolute inset-0 bg-black/0 group-hover/img:bg-black/20 transition-colors duration-300 flex items-center justify-center pointer-events-none">
                                            <svg class="w-8 h-8 text-white opacity-0 group-hover/img:opacity-100 transition-opacity duration-300 drop-shadow-lg" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7"/>
                                            </svg>
                                        </div>
                                    </div>
                                @endforeach

                                {{-- Modal --}}
                                <div x-show="open" 
                                     x-transition:enter="transition ease-out duration-300"
                                     x-transition:enter-start="opacity-0"
                                     x-transition:enter-end="opacity-100"
                                     x-transition:leave="transition ease-in duration-200"
                                     x-transition:leave-start="opacity-100"
                                     x-transition:leave-end="opacity-0"
                                     class="fixed inset-0 z-[100] flex items-center justify-center bg-black/95 backdrop-blur-sm p-4"
                                     style="display: none;">
                                    
                                    <div @click.away="open = false" class="relative w-full h-full flex items-center justify-center">
                                        {{-- Anterior --}}
                                        <button @click.stop="prev()" class="absolute left-4 z-10 p-2 rounded-full bg-white/10 hover:bg-white/20 text-white transition-colors focus:outline-none">
                                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                                            </svg>
                                        </button>

                                        <img :src="currentImage" class="max-w-full max-h-[90vh] object-contain rounded-lg shadow-2xl select-none" alt="Vista ampliada">
                                        
                                        {{-- Siguiente --}}
                                        <button @click.stop="next()" class="absolute right-4 z-10 p-2 rounded-full bg-white/10 hover:bg-white/20 text-white transition-colors focus:outline-none">
                                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                            </svg>
                                        </button>

                                        {{-- Cerrar --}}
                                        <button @click="open = false" class="absolute top-4 right-4 z-20 text-white/70 hover:text-white transition-colors">
                                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                            </svg>
                                        </button>
                                        
                                        {{-- Contador --}}
                                        <div class="absolute bottom-4 left-1/2 transform -translate-x-1/2 text-white/80 font-medium bg-black/50 px-3 py-1 rounded-full text-sm">
                                            <span x-text="currentIndex + 1"></span> / <span x-text="images.length"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @else
                            <div class="text-center py-8 bg-gray-50 rounded-xl border border-dashed border-gray-300">
                                <svg class="w-12 h-12 text-gray-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                                <p class="text-gray-500 text-sm">No hay imágenes disponibles para esta cancha.</p>
                            </div>
                        @endif
                    </div>
                </div>
                @endif

                <div class="bg-white rounded-3xl p-8 shadow-sm border border-gray-100 hover:shadow-xl hover:border-green-100 transition-all duration-300">
                    <h2 class="text-2xl font-bold text-gray-900 mb-6">Características</h2>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        {{-- Tipo --}}
                        <div class="flex items-start gap-4 p-4 rounded-2xl hover:bg-green-50/50 transition-all duration-300 group cursor-default">
                            <div class="w-12 h-12 rounded-2xl bg-green-50 flex items-center justify-center flex-shrink-0 group-hover:bg-green-100 group-hover:scale-110 transition-all duration-300">
                                <svg class="w-6 h-6 text-green-600 group-hover:scale-110 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm text-gray-400 font-medium mb-1 group-hover:text-green-600 transition-colors">Tipo de cancha</p>
                                <p class="text-lg font-semibold text-gray-900 group-hover:text-green-700 transition-colors">{{ $this->cancha->tipo }}</p>
                            </div>
                        </div>

                        {{-- Precio --}}
                        <div class="flex items-start gap-4 p-4 rounded-2xl hover:bg-green-50/50 transition-all duration-300 group cursor-default">
                            <div class="w-12 h-12 rounded-2xl bg-green-50 flex items-center justify-center flex-shrink-0 group-hover:bg-green-100 group-hover:scale-110 transition-all duration-300">
                                <svg class="w-6 h-6 text-green-600 group-hover:scale-110 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm text-gray-400 font-medium mb-1 group-hover:text-green-600 transition-colors">Precio por hora</p>
                                <p class="text-3xl font-bold text-green-600 group-hover:scale-105 transition-transform">${{ number_format($this->cancha->precio_hora, 2) }}</p>
                            </div>
                        </div>

                        {{-- Estado --}}
                        <div class="flex items-start gap-4 p-4 rounded-2xl hover:bg-green-50/50 transition-all duration-300 group cursor-default">
                            <div class="w-12 h-12 rounded-2xl bg-green-50 flex items-center justify-center flex-shrink-0 group-hover:bg-green-100 group-hover:scale-110 transition-all duration-300">
                                <svg class="w-6 h-6 text-green-600 group-hover:scale-110 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm text-gray-400 font-medium mb-1 group-hover:text-green-600 transition-colors">Estado</p>
                                <p class="text-lg font-semibold {{ $this->cancha->activa ? 'text-green-600' : 'text-red-600' }}">
                                    {{ $this->cancha->activa ? 'Disponible' : 'No disponible' }}
                                </p>
                            </div>
                        </div>

                        <div class="flex items-start gap-4 p-4 rounded-2xl hover:bg-green-50/50 transition-all duration-300 group cursor-default">
                            <div class="w-12 h-12 rounded-2xl bg-green-50 flex items-center justify-center flex-shrink-0 group-hover:bg-green-100 group-hover:scale-110 transition-all duration-300">
                                <svg class="w-6 h-6 text-green-600 group-hover:scale-110 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm text-gray-400 font-medium mb-1 group-hover:text-green-600 transition-colors">Horarios</p>
                                <p class="text-lg font-semibold text-gray-900 group-hover:text-green-700 transition-colors">8:00 AM - 10:00 PM</p>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <div class="lg:col-span-1">
                <div class="bg-white rounded-3xl p-8 shadow-lg border border-gray-100 sticky top-6 hover:shadow-2xl hover:border-green-100 transition-all duration-300">
                    <h2 class="text-2xl font-bold text-gray-900 mb-6">Información rápida</h2>

                    {{-- Precio destacado --}}
                    <div class="bg-gradient-to-br from-green-50 to-green-100 rounded-2xl p-6 mb-6 hover:from-green-100 hover:to-green-200 hover:shadow-lg hover:scale-105 transition-all duration-300 cursor-default group">
                        <p class="text-sm text-gray-600 font-medium mb-2 group-hover:text-green-700 transition-colors">Tarifa por hora</p>
                        <p class="text-4xl font-bold text-green-700 group-hover:text-green-800 group-hover:scale-105 transition-all">${{ number_format($this->cancha->precio_hora, 2) }}</p>
                    </div>

                    {{-- Detalles --}}
                    <div class="space-y-4">
                        <div class="flex items-center gap-3 p-3 rounded-xl hover:bg-green-50/50 transition-all duration-300 group cursor-default">
                            <div class="w-10 h-10 bg-green-50 rounded-xl flex items-center justify-center group-hover:bg-green-100 group-hover:scale-110 transition-all duration-300">
                                <svg class="w-5 h-5 text-green-600 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <div>
                                <p class="text-xs text-gray-400 font-medium group-hover:text-green-600 transition-colors">Horario</p>
                                <p class="text-sm font-semibold text-gray-900 group-hover:text-green-700 transition-colors">8:00 AM - 10:00 PM</p>
                            </div>
                        </div>

                        <div class="flex items-center gap-3 p-3 rounded-xl hover:bg-green-50/50 transition-all duration-300 group cursor-default">
                            <div class="w-10 h-10 bg-green-50 rounded-xl flex items-center justify-center group-hover:bg-green-100 group-hover:scale-110 transition-all duration-300">
                                <svg class="w-5 h-5 text-green-600 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                            </div>
                            <div>
                                <p class="text-xs text-gray-400 font-medium group-hover:text-green-600 transition-colors">Tipo</p>
                                <p class="text-sm font-semibold text-gray-900 group-hover:text-green-700 transition-colors">{{ $this->cancha->tipo }}</p>
                            </div>
                        </div>

                        <div class="flex items-center gap-3 p-3 rounded-xl hover:bg-green-50/50 transition-all duration-300 group cursor-default">
                            <div class="w-10 h-10 bg-green-50 rounded-xl flex items-center justify-center group-hover:bg-green-100 group-hover:scale-110 transition-all duration-300">
                                <svg class="w-5 h-5 text-green-600 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <div>
                                <p class="text-xs text-gray-400 font-medium group-hover:text-green-600 transition-colors">Estado</p>
                                <p class="text-sm font-semibold {{ $this->cancha->activa ? 'text-green-600' : 'text-red-600' }}">
                                    {{ $this->cancha->activa ? 'Disponible' : 'No disponible' }}
                                </p>
                            </div>
                        </div>
                    </div>

                    {{-- Nota --}}
                    <div class="mt-6 p-4 bg-blue-50 border border-blue-100 rounded-xl hover:bg-blue-100 hover:border-blue-200 hover:shadow-lg transition-all duration-300 group cursor-default">
                        <div class="flex items-start gap-3">
                            <svg class="w-5 h-5 text-blue-600 flex-shrink-0 mt-0.5 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <div>
                                <p class="text-xs font-semibold text-blue-900 mb-1 group-hover:text-blue-950 transition-colors">Información</p>
                                <p class="text-xs text-blue-700 group-hover:text-blue-800 transition-colors">Para realizar una reserva, dirígete al panel de administración o reservas.</p>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
            {{-- Calendario --}}
<div class="lg:col-span-3 mt-6">
    <div class="bg-white rounded-3xl p-4 sm:p-8 shadow-sm border border-gray-100 hover:shadow-xl hover:border-green-100 transition-all duration-300">

        {{-- Encabezado calendario --}}
        <div class="mb-6">
            <h2 class="text-xl sm:text-2xl font-bold text-gray-900 mb-4 hover:text-green-600 transition-colors">
                Calendario de Reservas
            </h2>

            {{-- Navegación mes --}}
            <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3">
                <div class="flex items-center gap-2 flex-1">
                    <button wire:click="mesAnterior"
                            class="w-10 h-10 rounded-xl bg-gray-100 hover:bg-green-500 hover:shadow-lg hover:scale-110 flex items-center justify-center transition-all duration-300 flex-shrink-0 group">
                        <svg class="w-5 h-5 text-gray-700 group-hover:text-white group-hover:-translate-x-0.5 transition-all" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                        </svg>
                    </button>

                    <div class="text-center flex-1">
                        <h3 class="text-lg sm:text-xl font-bold text-gray-900 hover:text-green-600 transition-colors cursor-default">{{ $this->nombreMes }} {{ $añoActual }}</h3>
                    </div>

                    <button wire:click="mesSiguiente"
                            class="w-10 h-10 rounded-xl bg-gray-100 hover:bg-green-500 hover:shadow-lg hover:scale-110 flex items-center justify-center transition-all duration-300 flex-shrink-0 group">
                        <svg class="w-5 h-5 text-gray-700 group-hover:text-white group-hover:translate-x-0.5 transition-all" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </button>
                </div>

                <button wire:click="mesHoy"
                        class="px-4 py-2 rounded-xl bg-green-500 hover:bg-green-600 hover:shadow-lg hover:scale-105 text-white text-sm font-semibold transition-all duration-300 w-full sm:w-auto">
                    Hoy
                </button>
            </div>
        </div>

        {{-- Grid calendario --}}
        <div class="border border-gray-200 rounded-2xl overflow-hidden">

            {{-- Días semana --}}
            <div class="grid grid-cols-7 bg-gray-50 border-b border-gray-200">
                @foreach(['L', 'M', 'X', 'J', 'V', 'S', 'D'] as $index => $dia)
                    <div class="py-2 sm:py-3 text-center text-xs sm:text-sm font-semibold text-gray-700 border-r border-gray-200 last:border-r-0">
                        <span class="hidden sm:inline">{{ ['Lun', 'Mar', 'Mié', 'Jue', 'Vie', 'Sáb', 'Dom'][$index] }}</span>
                        <span class="sm:hidden">{{ $dia }}</span>
                    </div>
                @endforeach
            </div>

            {{-- Días mes --}}
            <div class="grid grid-cols-7">
                @foreach($this->diasCalendario as $index => $fecha)
                    @php
                        $esHoy = $fecha && $fecha->isToday();
                        $esMesActual = $fecha !== null;
                        $reservasDelDia = $esMesActual ? $this->getReservasPorFecha($fecha) : collect();
                        $tieneReservas = $reservasDelDia->count() > 0;
                    @endphp

                    <div class="min-h-[80px] sm:min-h-[100px] md:min-h-[120px] border-r border-b border-gray-200 last:border-r-0 bg-white transition-all duration-300 p-1 sm:p-2 {{ $fecha ? 'cursor-pointer hover:bg-gradient-to-br hover:from-blue-50 hover:to-green-50 hover:shadow-lg hover:scale-105 hover:z-10 hover:border-green-300' : '' }} group/day"
                         @if($fecha) wire:click="verDetallesDia({{ $fecha->day }})" @endif>
                        @if($fecha)
                            {{-- Número día --}}
                            <div class="flex items-start justify-between mb-1">
                                <span class="text-xs sm:text-sm font-semibold {{ $esHoy ? 'w-5 h-5 sm:w-7 sm:h-7 bg-green-500 text-white rounded-full flex items-center justify-center text-[10px] sm:text-sm' : 'text-gray-700' }}">
                                    {{ $fecha->day }}
                                </span>

                                @if($tieneReservas)
                                    <span class="text-[10px] sm:text-xs bg-blue-100 text-blue-700 px-1 sm:px-2 py-0.5 rounded-full font-medium">
                                        {{ $reservasDelDia->count() }}
                                    </span>
                                @endif
                            </div>

                            {{-- Reservas día --}}
                            <div class="space-y-1 hidden sm:block">
                                @foreach($reservasDelDia->take(2) as $reserva)
                                    <div class="text-[10px] sm:text-xs p-1 sm:p-1.5 rounded {{ $reserva->estado === 'confirmada' ? 'bg-green-100 text-green-800 border-l-2 border-green-500' : ($reserva->estado === 'cancelada' ? 'bg-red-100 text-red-800 border-l-2 border-red-500' : ($reserva->estado === 'finalizada' ? 'bg-blue-100 text-blue-800 border-l-2 border-blue-500' : 'bg-yellow-100 text-yellow-800 border-l-2 border-yellow-500')) }} truncate">
                                        <div class="font-semibold truncate">{{ $reserva->fecha_inicio->format('H:i') }}</div>
                                        <div class="truncate opacity-75 hidden md:block">{{ Str::limit($reserva->cliente->nombre ?? 'Sin cliente', 15) }}</div>
                                    </div>
                                @endforeach

                                @if($reservasDelDia->count() > 2)
                                    <div class="text-[10px] text-gray-500 font-medium pl-1">
                                        +{{ $reservasDelDia->count() - 2 }}
                                    </div>
                                @endif
                            </div>

                            {{-- Indicador móvil --}}
                            @if($tieneReservas)
                                <div class="sm:hidden flex gap-0.5 mt-1">
                                    @foreach($reservasDelDia->take(3) as $reserva)
                                        <div class="w-1.5 h-1.5 rounded-full {{ $reserva->estado === 'confirmada' ? 'bg-green-500' : ($reserva->estado === 'cancelada' ? 'bg-red-500' : ($reserva->estado === 'finalizada' ? 'bg-blue-500' : 'bg-yellow-500')) }}"></div>
                                    @endforeach
                                </div>
                            @endif
                        @else
                            {{-- Día vacío --}}
                            <div class="text-gray-300"></div>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>

        {{-- Leyenda --}}
        <div class="mt-4 sm:mt-6 grid grid-cols-2 sm:flex sm:flex-wrap items-center gap-3 sm:gap-6 text-xs sm:text-sm">
            <div class="flex items-center gap-2">
                <div class="w-3 h-3 sm:w-4 sm:h-4 bg-green-100 border-l-2 border-green-500 rounded"></div>
                <span class="text-gray-600">Confirmada</span>
            </div>
            <div class="flex items-center gap-2">
                <div class="w-3 h-3 sm:w-4 sm:h-4 bg-yellow-100 border-l-2 border-yellow-500 rounded"></div>
                <span class="text-gray-600">Pendiente</span>
            </div>
            <div class="flex items-center gap-2">
                <div class="w-3 h-3 sm:w-4 sm:h-4 bg-red-100 border-l-2 border-red-500 rounded"></div>
                <span class="text-gray-600">Cancelada</span>
            </div>
            <div class="flex items-center gap-2">
                <div class="w-3 h-3 sm:w-4 sm:h-4 bg-blue-100 border-l-2 border-blue-500 rounded"></div>
                <span class="text-gray-600">Finalizada</span>
            </div>
        </div>

    </div>
</div>

        </div>
    </div>

    {{-- Modal detalles --}}
    @if($mostrarModal && $diaSeleccionado)
        <div class="fixed inset-0 z-50 overflow-y-auto" wire:click="cerrarModal">
            {{-- Overlay --}}
            <div class="fixed inset-0 bg-black/50 backdrop-blur-sm transition-opacity"></div>

            {{-- Modal --}}
            <div class="flex min-h-screen items-end sm:items-center justify-center p-0 sm:p-4">
                <div class="relative bg-white rounded-t-3xl sm:rounded-3xl shadow-2xl w-full max-w-4xl max-h-[95vh] sm:max-h-[90vh] overflow-hidden"
                     wire:click.stop>

                    {{-- Encabezado modal --}}
                    <div class="bg-gradient-to-r from-green-600 to-green-500 px-3 sm:px-6 py-3 sm:py-5 text-white">
                        <div class="flex items-center justify-between mb-3 sm:mb-0">
                            <div class="flex-1 min-w-0">
                                <h3 class="text-lg sm:text-2xl font-bold truncate">
                                    {{ $diaSeleccionado->format('d') }} de {{ $this->nombreMes }}
                                </h3>
                                <p class="text-green-100 text-xs sm:text-base">
                                    {{ $diaSeleccionado->locale('es')->isoFormat('dddd') }}
                                </p>
                            </div>
                            <button wire:click="cerrarModal"
                                    class="w-9 h-9 sm:w-10 sm:h-10 rounded-full bg-white/20 hover:bg-white/30 flex items-center justify-center transition-colors flex-shrink-0 ml-2">
                                <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            </button>
                        </div>

                        {{-- Resumen día --}}
                        <div class="grid grid-cols-3 gap-1.5 sm:gap-4 mt-3">
                            <div class="bg-white/10 backdrop-blur-sm rounded-lg sm:rounded-xl px-2 py-1.5 sm:px-4 sm:py-3">
                                <p class="text-[10px] sm:text-xs text-green-100 mb-0.5">Reservas</p>
                                <p class="text-base sm:text-2xl font-bold">{{ $this->reservasDiaSeleccionado->count() }}</p>
                            </div>
                            <div class="bg-white/10 backdrop-blur-sm rounded-lg sm:rounded-xl px-2 py-1.5 sm:px-4 sm:py-3">
                                <p class="text-[10px] sm:text-xs text-green-100 mb-0.5">Ingresos</p>
                                <p class="text-base sm:text-2xl font-bold">${{ number_format($this->reservasDiaSeleccionado->sum('total'), 0) }}</p>
                            </div>
                            <div class="bg-white/10 backdrop-blur-sm rounded-lg sm:rounded-xl px-2 py-1.5 sm:px-4 sm:py-3">
                                <p class="text-[10px] sm:text-xs text-green-100 mb-0.5">Ocupación</p>
                                <p class="text-base sm:text-2xl font-bold">
                                    {{ $this->reservasDiaSeleccionado->count() > 0 ? round(($this->reservasDiaSeleccionado->sum('duracion_minutos') / (14 * 60)) * 100) : 0 }}%
                                </p>
                            </div>
                        </div>
                    </div>

                    {{-- Timeline --}}
                    <div class="p-2 sm:p-6 overflow-y-auto max-h-[calc(95vh-220px)] sm:max-h-[calc(90vh-280px)]">

                        @if($this->reservasDiaSeleccionado->count() > 0)
                            {{-- Vista timeline --}}
                            <div class="space-y-0 border border-gray-200 rounded-xl sm:rounded-2xl overflow-hidden">
                                @php
                                    $reservasMostradas = [];
                                @endphp

                                @foreach($this->bloquesHorarios as $horaBloque)
                                    @php
                                        $reserva = $this->getBloqueConReserva($horaBloque);
                                        $esInicio = $reserva && $this->esInicioReserva($reserva, $horaBloque);
                                        $yaFueMostrada = $reserva && in_array($reserva->id, $reservasMostradas);

                                        if ($esInicio) {
                                            $reservasMostradas[] = $reserva->id;
                                        }
                                    @endphp

                                    @if($esInicio)
                                        {{-- Bloque reserva --}}
                                        @php
                                            $bloques = $this->calcularBloques($reserva);
                                            $alturaBloque = 60; // px por bloque de 30 min
                                            $altura = $bloques * $alturaBloque;
                                        @endphp

                                        <div class="flex flex-col sm:flex-row border-b border-gray-200 hover:bg-gray-50/50 transition-colors">

                                            {{-- Columna hora --}}
                                            <div class="w-full sm:w-20 md:w-24 flex-shrink-0 p-2 sm:p-3 md:p-4 border-b sm:border-b-0 sm:border-r border-gray-200 bg-gray-50">
                                                <div class="flex sm:flex-col items-center sm:items-start justify-between sm:justify-start gap-2 sm:gap-1">
                                                    <div class="flex items-center gap-2 sm:flex-col sm:items-start sm:gap-1">
                                                        <span class="text-sm sm:text-xs md:text-sm font-bold text-gray-900">{{ $reserva->fecha_inicio->format('H:i') }}</span>
                                                        <span class="text-gray-400">-</span>
                                                        <span class="text-sm sm:text-[10px] md:text-xs text-gray-600 sm:text-gray-500">{{ $reserva->fecha_fin->format('H:i') }}</span>
                                                    </div>
                                                    <span class="px-2 py-0.5 bg-gray-100 rounded-full text-[10px] sm:text-[10px] text-gray-600 font-medium sm:mt-1">{{ $reserva->duracion_minutos }}min</span>
                                                </div>
                                            </div>

                                            {{-- Contenido reserva --}}
                                            <div class="flex-1 p-3 sm:p-3 md:p-4 {{ $reserva->estado === 'confirmada' ? 'bg-green-50 border-l-2 sm:border-l-4 border-green-500' : ($reserva->estado === 'cancelada' ? 'bg-red-50 border-l-2 sm:border-l-4 border-red-500' : ($reserva->estado === 'finalizada' ? 'bg-blue-50 border-l-2 sm:border-l-4 border-blue-500' : 'bg-yellow-50 border-l-2 sm:border-l-4 border-yellow-500')) }}">

                                                {{-- Info principal --}}
                                                <div class="space-y-2 sm:space-y-0 sm:flex sm:items-start sm:justify-between mb-2">
                                                    <div class="flex-1 min-w-0">
                                                        {{-- Nombre --}}
                                                        <div class="flex items-center gap-2 mb-2">
                                                            <svg class="w-4 h-4 text-gray-700 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                                            </svg>
                                                            <h4 class="text-sm sm:text-base md:text-lg font-bold text-gray-900 truncate">
                                                                {{ $reserva->cliente->nombre ?? 'Sin cliente' }}
                                                            </h4>
                                                        </div>

                                                        {{-- Detalles secundarios --}}
                                                        <div class="space-y-1">
                                                            @if($reserva->cliente && $reserva->cliente->equipo)
                                                                <div class="flex items-center gap-1.5 text-gray-600">
                                                                    <svg class="w-3 h-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                                                                    </svg>
                                                                    <span class="text-xs truncate">{{ $reserva->cliente->equipo }}</span>
                                                                </div>
                                                            @endif

                                                            @if($reserva->cliente && $reserva->cliente->telefono)
                                                                <div class="flex items-center gap-1.5 text-gray-600">
                                                                    <svg class="w-3 h-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                                                                    </svg>
                                                                    <span class="text-xs">{{ $reserva->cliente->telefono }}</span>
                                                                </div>
                                                            @endif
                                                        </div>
                                                    </div>

                                                    {{-- Badges --}}
                                                    <div class="flex sm:flex-col items-center sm:items-end gap-2 sm:ml-2">
                                                        <span class="px-2 py-1 rounded-lg sm:rounded-full text-[10px] sm:text-xs font-semibold whitespace-nowrap {{ $reserva->estado === 'confirmada' ? 'bg-green-100 text-green-700 ring-1 ring-green-600/20' : ($reserva->estado === 'cancelada' ? 'bg-red-100 text-red-700 ring-1 ring-red-600/20' : ($reserva->estado === 'finalizada' ? 'bg-blue-100 text-blue-700 ring-1 ring-blue-600/20' : 'bg-yellow-100 text-yellow-700 ring-1 ring-yellow-600/20')) }}">
                                                            {{ ucfirst($reserva->estado) }}
                                                        </span>
                                                        <div class="flex items-center gap-1 bg-white px-2 py-1 rounded-lg shadow-sm">
                                                            <svg class="w-3 h-3 text-green-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                            </svg>
                                                            <span class="text-xs sm:text-sm font-bold text-gray-900">${{ number_format($reserva->total, 2) }}</span>
                                                        </div>
                                                    </div>
                                                </div>

                                                @if($reserva->observaciones)
                                                    <div class="mt-2 p-2 bg-white/60 rounded-lg border border-gray-200">
                                                        <p class="text-[10px] text-gray-500 font-semibold mb-1">Observaciones:</p>
                                                        <p class="text-xs text-gray-700 line-clamp-2">{{ $reserva->observaciones }}</p>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>

                                    @elseif(!$reserva && str_ends_with($horaBloque, ':00'))
                                        {{-- Bloque disponible --}}
                                        <div class="hidden sm:flex border-b border-gray-100 hover:bg-green-50/30 transition-colors"
                                             style="min-height: 50px;">
                                            <div class="w-20 md:w-24 flex-shrink-0 p-3 md:p-4 border-r border-gray-200 bg-gray-50">
                                                <span class="text-xs md:text-sm font-semibold text-gray-500">{{ $horaBloque }}</span>
                                            </div>
                                            <div class="flex-1 p-3 md:p-4 flex items-center">
                                                <div class="flex items-center gap-2 text-gray-400">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                    </svg>
                                                    <span class="text-xs md:text-sm italic">Disponible</span>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                @endforeach
                            </div>

                        @else
                            {{-- Sin reservas --}}
                            <div class="text-center py-12">
                                <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                    <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                </div>
                                <p class="text-gray-500 font-medium">No hay reservas para este día</p>
                                <p class="text-gray-400 text-sm mt-1">La cancha está completamente disponible</p>
                            </div>
                        @endif

                    </div>

                    {{-- Pie modal --}}
                    <div class="border-t border-gray-200 px-3 sm:px-6 py-2 sm:py-4 bg-gray-50 flex flex-col-reverse sm:flex-row items-stretch sm:items-center justify-between gap-2 sm:gap-3">
                        <div class="text-[10px] sm:text-sm text-gray-600 text-center sm:text-left">
                            Horario: <span class="font-semibold">8:00 AM - 10:00 PM</span>
                        </div>
                        <button wire:click="cerrarModal"
                                class="w-full sm:w-auto px-4 sm:px-6 py-2.5 sm:py-3 bg-gray-200 hover:bg-gray-300 text-gray-800 font-semibold rounded-xl transition-colors text-sm sm:text-base">
                            Cerrar
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

</div>
