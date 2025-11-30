<div class="min-h-screen bg-gradient-to-br from-gray-50 via-white to-gray-50">

    <!-- Hero Section con Imagen -->
    <div class="relative h-96 overflow-hidden">
        <img src="{{ $this->cancha->imagen_url ? asset($this->cancha->imagen_url) : asset('images/default-cancha.jpg') }}"
             class="w-full h-full object-cover"
             alt="{{ $this->cancha->nombre }}">

        <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/40 to-transparent"></div>

        <!-- Contenido sobre la imagen -->
        <div class="absolute inset-0 flex items-end">
            <div class="container mx-auto px-6 pb-12">

                <div class="mb-4">
                    @if($this->cancha->activa)
                        <span class="inline-flex items-center gap-2 px-4 py-2 rounded-full text-sm font-semibold bg-white/95 backdrop-blur-md text-green-700 shadow-lg ring-1 ring-green-600/20">
                            <span class="w-2.5 h-2.5 bg-green-500 rounded-full animate-pulse"></span>
                            Disponible para reservar
                        </span>
                    @else
                        <span class="inline-flex items-center gap-2 px-4 py-2 rounded-full text-sm font-semibold bg-white/95 backdrop-blur-md text-red-700 shadow-lg ring-1 ring-red-600/20">
                            <span class="w-2.5 h-2.5 bg-red-500 rounded-full"></span>
                            No disponible
                        </span>
                    @endif
                </div>

                <!-- Título y tipo -->
                <h1 class="text-5xl font-bold text-white mb-3 tracking-tight">{{ $this->cancha->nombre }}</h1>
                <p class="text-xl text-gray-200">{{ $this->cancha->tipo }}</p>
            </div>
        </div>

        <a href="{{ route('estadios.index') }}"
           class="absolute top-6 left-6 w-12 h-12 rounded-full bg-white/90 backdrop-blur-md shadow-lg flex items-center justify-center hover:bg-white transition-all">
            <svg class="w-6 h-6 text-gray-900" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
        </a>
    </div>

    <!-- Contenido Principal -->
    <div class="container mx-auto px-6 py-12">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

            <div class="lg:col-span-2 space-y-6">

                @if($this->cancha->descripcion)
                <div class="bg-white rounded-3xl p-8 shadow-sm border border-gray-100">
                    <h2 class="text-2xl font-bold text-gray-900 mb-4">Acerca de esta cancha</h2>
                    <p class="text-gray-600 leading-relaxed">{{ $this->cancha->descripcion }}</p>
                </div>
                @endif

                <div class="bg-white rounded-3xl p-8 shadow-sm border border-gray-100">
                    <h2 class="text-2xl font-bold text-gray-900 mb-6">Características</h2>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Tipo -->
                        <div class="flex items-start gap-4">
                            <div class="w-12 h-12 rounded-2xl bg-green-50 flex items-center justify-center flex-shrink-0">
                                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm text-gray-400 font-medium mb-1">Tipo de cancha</p>
                                <p class="text-lg font-semibold text-gray-900">{{ $this->cancha->tipo }}</p>
                            </div>
                        </div>

                        <!-- Precio -->
                        <div class="flex items-start gap-4">
                            <div class="w-12 h-12 rounded-2xl bg-green-50 flex items-center justify-center flex-shrink-0">
                                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm text-gray-400 font-medium mb-1">Precio por hora</p>
                                <p class="text-3xl font-bold text-green-600">${{ number_format($this->cancha->precio_hora, 2) }}</p>
                            </div>
                        </div>

                        <!-- Estado -->
                        <div class="flex items-start gap-4">
                            <div class="w-12 h-12 rounded-2xl bg-green-50 flex items-center justify-center flex-shrink-0">
                                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm text-gray-400 font-medium mb-1">Estado</p>
                                <p class="text-lg font-semibold {{ $this->cancha->activa ? 'text-green-600' : 'text-red-600' }}">
                                    {{ $this->cancha->activa ? 'Disponible' : 'No disponible' }}
                                </p>
                            </div>
                        </div>

                        <div class="flex items-start gap-4">
                            <div class="w-12 h-12 rounded-2xl bg-green-50 flex items-center justify-center flex-shrink-0">
                                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm text-gray-400 font-medium mb-1">Horarios</p>
                                <p class="text-lg font-semibold text-gray-900">8:00 AM - 10:00 PM</p>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <div class="lg:col-span-1">
                <div class="bg-white rounded-3xl p-8 shadow-lg border border-gray-100 sticky top-6">
                    <h2 class="text-2xl font-bold text-gray-900 mb-6">Información rápida</h2>

                    <!-- Precio destacado -->
                    <div class="bg-gradient-to-br from-green-50 to-green-100 rounded-2xl p-6 mb-6">
                        <p class="text-sm text-gray-600 font-medium mb-2">Tarifa por hora</p>
                        <p class="text-4xl font-bold text-green-700">${{ number_format($this->cancha->precio_hora, 2) }}</p>
                    </div>

                    <!-- Detalles adicionales -->
                    <div class="space-y-4">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-green-50 rounded-xl flex items-center justify-center">
                                <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <div>
                                <p class="text-xs text-gray-400 font-medium">Horario</p>
                                <p class="text-sm font-semibold text-gray-900">8:00 AM - 10:00 PM</p>
                            </div>
                        </div>

                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-green-50 rounded-xl flex items-center justify-center">
                                <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                            </div>
                            <div>
                                <p class="text-xs text-gray-400 font-medium">Tipo</p>
                                <p class="text-sm font-semibold text-gray-900">{{ $this->cancha->tipo }}</p>
                            </div>
                        </div>

                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-green-50 rounded-xl flex items-center justify-center">
                                <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <div>
                                <p class="text-xs text-gray-400 font-medium">Estado</p>
                                <p class="text-sm font-semibold {{ $this->cancha->activa ? 'text-green-600' : 'text-red-600' }}">
                                    {{ $this->cancha->activa ? 'Disponible' : 'No disponible' }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Nota informativa -->
                    <div class="mt-6 p-4 bg-blue-50 border border-blue-100 rounded-xl">
                        <div class="flex items-start gap-3">
                            <svg class="w-5 h-5 text-blue-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <div>
                                <p class="text-xs font-semibold text-blue-900 mb-1">Información</p>
                                <p class="text-xs text-blue-700">Para realizar una reserva, dirígete al panel de administración o reservas.</p>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
            <!-- Calendario de Reservas -->
<div class="lg:col-span-3 mt-6">
    <div class="bg-white rounded-3xl p-4 sm:p-8 shadow-sm border border-gray-100">

        <!-- Header del Calendario - Responsive -->
        <div class="mb-6">
            <h2 class="text-xl sm:text-2xl font-bold text-gray-900 mb-4">
                Calendario de Reservas
            </h2>

            <!-- Navegación del mes - Stack en móvil -->
            <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3">
                <div class="flex items-center gap-2 flex-1">
                    <button wire:click="mesAnterior"
                            class="w-10 h-10 rounded-xl bg-gray-100 hover:bg-gray-200 flex items-center justify-center transition-colors flex-shrink-0">
                        <svg class="w-5 h-5 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                        </svg>
                    </button>

                    <div class="text-center flex-1">
                        <h3 class="text-lg sm:text-xl font-bold text-gray-900">{{ $this->nombreMes }} {{ $añoActual }}</h3>
                    </div>

                    <button wire:click="mesSiguiente"
                            class="w-10 h-10 rounded-xl bg-gray-100 hover:bg-gray-200 flex items-center justify-center transition-colors flex-shrink-0">
                        <svg class="w-5 h-5 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </button>
                </div>

                <button wire:click="mesHoy"
                        class="px-4 py-2 rounded-xl bg-green-500 hover:bg-green-600 text-white text-sm font-semibold transition-colors w-full sm:w-auto">
                    Hoy
                </button>
            </div>
        </div>

        <!-- Grid del Calendario - Responsive -->
        <div class="border border-gray-200 rounded-2xl overflow-hidden">

            <!-- Días de la semana -->
            <div class="grid grid-cols-7 bg-gray-50 border-b border-gray-200">
                @foreach(['L', 'M', 'X', 'J', 'V', 'S', 'D'] as $index => $dia)
                    <div class="py-2 sm:py-3 text-center text-xs sm:text-sm font-semibold text-gray-700 border-r border-gray-200 last:border-r-0">
                        <span class="hidden sm:inline">{{ ['Lun', 'Mar', 'Mié', 'Jue', 'Vie', 'Sáb', 'Dom'][$index] }}</span>
                        <span class="sm:hidden">{{ $dia }}</span>
                    </div>
                @endforeach
            </div>

            <!-- Días del mes -->
            <div class="grid grid-cols-7">
                @foreach($this->diasCalendario as $index => $fecha)
                    @php
                        $esHoy = $fecha && $fecha->isToday();
                        $esMesActual = $fecha !== null;
                        $reservasDelDia = $esMesActual ? $this->getReservasPorFecha($fecha) : collect();
                        $tieneReservas = $reservasDelDia->count() > 0;
                    @endphp

                    <div class="min-h-[80px] sm:min-h-[100px] md:min-h-[120px] border-r border-b border-gray-200 last:border-r-0 bg-white transition-colors p-1 sm:p-2 {{ $fecha ? 'cursor-pointer hover:bg-blue-50 hover:shadow-inner' : '' }}"
                         @if($fecha) wire:click="verDetallesDia({{ $fecha->day }})" @endif>
                        @if($fecha)
                            <!-- Número del día -->
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

                            <!-- Reservas del día - Solo mostrar en tablets y desktop -->
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

                            <!-- Indicador de reservas en móvil - Mostrar solo puntos -->
                            @if($tieneReservas)
                                <div class="sm:hidden flex gap-0.5 mt-1">
                                    @foreach($reservasDelDia->take(3) as $reserva)
                                        <div class="w-1.5 h-1.5 rounded-full {{ $reserva->estado === 'confirmada' ? 'bg-green-500' : ($reserva->estado === 'cancelada' ? 'bg-red-500' : ($reserva->estado === 'finalizada' ? 'bg-blue-500' : 'bg-yellow-500')) }}"></div>
                                    @endforeach
                                </div>
                            @endif
                        @else
                            <!-- Día vacío (fuera del mes) -->
                            <div class="text-gray-300"></div>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Leyenda - Responsive -->
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

    <!-- Modal de Detalles del Día -->
    @if($mostrarModal && $diaSeleccionado)
        <div class="fixed inset-0 z-50 overflow-y-auto" wire:click="cerrarModal">
            <!-- Overlay -->
            <div class="fixed inset-0 bg-black/50 backdrop-blur-sm transition-opacity"></div>

            <!-- Modal -->
            <div class="flex min-h-screen items-center justify-center p-4">
                <div class="relative bg-white rounded-3xl shadow-2xl max-w-2xl w-full max-h-[90vh] overflow-hidden"
                     wire:click.stop>

                    <!-- Header del Modal -->
                    <div class="bg-gradient-to-r from-green-600 to-green-500 px-6 py-6 text-white">
                        <div class="flex items-center justify-between">
                            <div>
                                <h3 class="text-2xl font-bold">
                                    {{ $diaSeleccionado->format('d') }} de {{ $this->nombreMes }}
                                </h3>
                                <p class="text-green-100 mt-1">
                                    {{ $diaSeleccionado->locale('es')->isoFormat('dddd') }}
                                </p>
                            </div>
                            <button wire:click="cerrarModal"
                                    class="w-10 h-10 rounded-full bg-white/20 hover:bg-white/30 flex items-center justify-center transition-colors">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            </button>
                        </div>
                    </div>

                    <!-- Contenido del Modal -->
                    <div class="p-6 overflow-y-auto max-h-[calc(90vh-140px)]">
                        @forelse($this->reservasDiaSeleccionado as $reserva)
                            <div class="mb-4 p-4 rounded-2xl border-l-4 {{ $reserva->estado === 'confirmada' ? 'bg-green-50 border-green-500' : ($reserva->estado === 'cancelada' ? 'bg-red-50 border-red-500' : ($reserva->estado === 'finalizada' ? 'bg-blue-50 border-blue-500' : 'bg-yellow-50 border-yellow-500')) }} hover:shadow-md transition-shadow">

                                <!-- Header de la reserva -->
                                <div class="flex items-start justify-between mb-3">
                                    <div class="flex-1">
                                        <div class="flex items-center gap-2 mb-2">
                                            <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                            <span class="text-lg font-bold text-gray-900">
                                                {{ $reserva->fecha_inicio->format('H:i') }} - {{ $reserva->fecha_fin->format('H:i') }}
                                            </span>
                                            <span class="text-sm text-gray-500">
                                                ({{ $reserva->duracion_minutos }} min)
                                            </span>
                                        </div>
                                    </div>

                                    <span class="px-3 py-1 rounded-full text-xs font-semibold {{ $reserva->estado === 'confirmada' ? 'bg-green-100 text-green-700' : ($reserva->estado === 'cancelada' ? 'bg-red-100 text-red-700' : ($reserva->estado === 'finalizada' ? 'bg-blue-100 text-blue-700' : 'bg-yellow-100 text-yellow-700')) }}">
                                        {{ ucfirst($reserva->estado) }}
                                    </span>
                                </div>

                                <!-- Detalles de la reserva -->
                                <div class="space-y-2">
                                    <div class="flex items-center gap-2">
                                        <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                        </svg>
                                        <span class="text-sm font-semibold text-gray-900">
                                            {{ $reserva->cliente->nombre ?? 'Sin cliente asignado' }}
                                        </span>
                                    </div>

                                    @if($reserva->cliente && $reserva->cliente->equipo)
                                        <div class="flex items-center gap-2">
                                            <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                                            </svg>
                                            <span class="text-sm text-gray-600">{{ $reserva->cliente->equipo }}</span>
                                        </div>
                                    @endif

                                    @if($reserva->cliente && $reserva->cliente->telefono)
                                        <div class="flex items-center gap-2">
                                            <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                                            </svg>
                                            <span class="text-sm text-gray-600">{{ $reserva->cliente->telefono }}</span>
                                        </div>
                                    @endif

                                    <div class="flex items-center gap-2">
                                        <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        <span class="text-sm font-bold text-green-700">${{ number_format($reserva->total, 2) }}</span>
                                    </div>

                                    @if($reserva->observaciones)
                                        <div class="mt-3 p-3 bg-gray-50 rounded-xl">
                                            <p class="text-xs text-gray-500 font-semibold mb-1">Observaciones:</p>
                                            <p class="text-sm text-gray-700 italic">{{ $reserva->observaciones }}</p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-12">
                                <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                    <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                </div>
                                <p class="text-gray-500 font-medium">No hay reservas para este día</p>
                                <p class="text-gray-400 text-sm mt-1">La cancha está disponible</p>
                            </div>
                        @endforelse
                    </div>

                    <!-- Footer del Modal -->
                    <div class="border-t border-gray-200 px-6 py-4 bg-gray-50">
                        <button wire:click="cerrarModal"
                                class="w-full py-3 bg-gray-200 hover:bg-gray-300 text-gray-800 font-semibold rounded-xl transition-colors">
                            Cerrar
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

</div>
