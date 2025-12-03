<div class="space-y-4 sm:space-y-8">
    <div class="rounded-2xl sm:rounded-3xl bg-gradient-to-r from-green-900 via-green-800 to-green-700 p-4 sm:p-6 md:p-8 shadow-2xl text-white">
        <div class="flex flex-col gap-4 sm:gap-6 md:flex-row md:items-center md:justify-between">
            <div class="space-y-2 sm:space-y-3 max-w-2xl">
                <p class="text-xs sm:text-sm uppercase tracking-[0.3em] sm:tracking-[0.4em] text-green-100">Calendario</p>
                <h2 class="text-xl sm:text-2xl md:text-3xl font-bold">Agenda mensual inteligente</h2>
                <p class="text-green-50 text-xs sm:text-sm leading-relaxed">
                    Visualiza en un solo golpe de vista qué días tienen reservas confirmadas y accede al detalle
                    de cada jornada sin salir del panel.
                </p>
            </div>
            <div class="w-full md:w-auto">
                <label class="text-xs uppercase tracking-widest text-green-100">Visualizar mes</label>
                <div class="mt-2 rounded-2xl bg-white/10 p-3 backdrop-blur">
                    <select wire:model.live="selectedPeriod" wire:loading.attr="disabled"
                        class="w-full bg-transparent text-white font-semibold focus:outline-none">
                        @foreach ($monthOptions as $value => $label)
                            <option class="text-gray-900" value="{{ $value }}">{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        <div class="mt-4 sm:mt-6 md:mt-8 grid gap-3 sm:gap-4 grid-cols-2 md:grid-cols-3 text-white">
            <div class="rounded-xl sm:rounded-2xl border border-green-400/40 bg-green-600/20 p-3 sm:p-4">
                <p class="text-[10px] sm:text-xs uppercase tracking-[0.3em] sm:tracking-[0.4em] text-green-100">Reservas</p>
                <p class="mt-1 sm:mt-2 text-2xl sm:text-3xl font-bold">{{ $totalMonthReservations }}</p>
                <p class="text-[10px] sm:text-xs text-green-50 truncate">Registros en {{ $focusedMonthLabel }}</p>
            </div>
            <div class="rounded-xl sm:rounded-2xl border border-green-300/40 bg-green-500/20 p-3 sm:p-4">
                <p class="text-[10px] sm:text-xs uppercase tracking-[0.3em] sm:tracking-[0.4em] text-green-100">Días activos</p>
                <p class="mt-1 sm:mt-2 text-2xl sm:text-3xl font-bold">{{ $busyDaysCount }}</p>
                <p class="text-[10px] sm:text-xs text-green-50">Con reservas</p>
            </div>
            <div class="rounded-xl sm:rounded-2xl border border-green-200/40 bg-green-400/20 p-3 sm:p-4 col-span-2 md:col-span-1">
                <p class="text-[10px] sm:text-xs uppercase tracking-[0.3em] sm:tracking-[0.4em] text-green-100">Estado actual</p>
                <p class="mt-1 sm:mt-2 text-base sm:text-lg font-semibold">{{ $focusedMonthLabel }}</p>
                <p class="text-[10px] sm:text-xs text-green-50">Zona horaria America/El_Salvador</p>
            </div>
        </div>
    </div>

    <div class="rounded-2xl sm:rounded-3xl border border-green-800 bg-white/95 p-3 sm:p-4 md:p-6 shadow-2xl">
        <div class="flex flex-col gap-2 sm:gap-3 md:flex-row md:items-center md:justify-between">
            <div>
                <h3 class="text-base sm:text-lg md:text-xl font-semibold text-gray-900">Calendario del mes</h3>
                <p class="text-xs sm:text-sm text-gray-600">
                    Selecciona cualquier día resaltado en verde para conocer las reservas registradas.
                </p>
            </div>
            <div class="flex items-center gap-2 sm:gap-3 text-[10px] sm:text-xs text-gray-600 flex-wrap">
                <span class="inline-flex items-center gap-1.5 sm:gap-2">
                    <span class="h-2.5 w-2.5 sm:h-3 sm:w-3 rounded-full bg-green-500"></span>Con reservas
                </span>
                <span class="inline-flex items-center gap-1.5 sm:gap-2">
                    <span class="h-2.5 w-2.5 sm:h-3 sm:w-3 rounded-full border border-gray-400"></span>Sin reservas
                </span>
            </div>
        </div>

        <div class="mt-4 sm:mt-6 grid grid-cols-7 text-center text-[10px] sm:text-xs font-semibold uppercase tracking-wider sm:tracking-widest text-gray-600">
            <span class="hidden sm:inline">Dom</span><span class="sm:hidden">D</span>
            <span class="hidden sm:inline">Lun</span><span class="sm:hidden">L</span>
            <span class="hidden sm:inline">Mar</span><span class="sm:hidden">M</span>
            <span class="hidden sm:inline">Mié</span><span class="sm:hidden">X</span>
            <span class="hidden sm:inline">Jue</span><span class="sm:hidden">J</span>
            <span class="hidden sm:inline">Vie</span><span class="sm:hidden">V</span>
            <span class="hidden sm:inline">Sáb</span><span class="sm:hidden">S</span>
        </div>
        <div class="mt-6 flex items-center justify-between text-[11px] font-semibold uppercase tracking-[0.4em] text-gray-600 md:hidden">
            <span>Días del mes</span>
            <span>{{ $focusedMonthLabel }}</span>
        </div>

        <div class="mt-2 grid grid-cols-7 gap-1 sm:gap-2">
            @foreach ($this->calendarDays as $day)
                @php
                    $baseClasses = 'relative min-h-[60px] sm:min-h-[75px] md:min-h-[90px] rounded-lg sm:rounded-xl md:rounded-2xl border px-1.5 py-2 sm:px-2 sm:py-3 md:px-3 md:py-4 text-left transition-all';
                    $stateClasses = match (true) {
                        ! $day['isCurrentMonth'] => 'border-gray-300 bg-gray-100 text-gray-400',
                        $day['isToday'] => 'border-green-500 bg-green-50 text-gray-900 shadow-lg shadow-green-500/20',
                        default => 'border-gray-300 bg-white text-gray-700',
                    };
                    $interactivityClasses = $day['hasReservations']
                        ? 'cursor-pointer hover:-translate-y-1 hover:shadow-xl hover:shadow-green-500/30'
                        : 'cursor-not-allowed opacity-50';
                @endphp
                <button type="button"
                    data-calendar-day
                    data-has-reservations="{{ $day['hasReservations'] ? 'true' : 'false' }}"
                    @if ($day['hasReservations']) wire:click="openDay('{{ $day['date'] }}')" @endif
                    @if (! $day['hasReservations']) disabled @endif
                    class="{{ $baseClasses }} {{ $stateClasses }} {{ $interactivityClasses }}">
                    <div class="flex items-start justify-between">
                        <span class="text-base sm:text-xl md:text-2xl font-semibold">{{ $day['label'] }}</span>
                        @if ($day['hasReservations'])
                            <span class="h-1.5 w-1.5 sm:h-2 sm:w-2 rounded-full bg-green-500"></span>
                        @endif
                    </div>
                    <p class="mt-0.5 sm:mt-1 text-[9px] sm:text-xs text-gray-500 hidden sm:block">{{ \Carbon\Carbon::parse($day['date'])->isoFormat('DD MMM') }}</p>
                    @if ($day['hasReservations'])
                        <span class="mt-1 sm:mt-2 md:mt-4 inline-flex items-center gap-1 sm:gap-2 rounded-full bg-green-100 px-1.5 py-0.5 sm:px-2 sm:py-1 md:px-3 text-[9px] sm:text-[10px] md:text-[11px] font-semibold text-green-700">
                            <span class="h-1.5 w-1.5 sm:h-2 sm:w-2 animate-pulse rounded-full bg-green-500 hidden sm:inline"></span>
                            <span class="sm:hidden">{{ $day['reservationsCount'] }}</span>
                            <span class="hidden sm:inline">{{ $day['reservationsCount'] }} reservas</span>
                        </span>
                    @else
                        <span class="mt-1 sm:mt-2 md:mt-4 inline-flex items-center rounded-full border border-gray-300 px-1.5 py-0.5 sm:px-2 sm:py-1 md:px-3 text-[9px] sm:text-[10px] md:text-[11px] font-semibold text-gray-500 hidden sm:inline-flex">
                            Libre
                        </span>
                    @endif
                </button>
            @endforeach
        </div>
    </div>

    <div data-calendar-modal wire:click.self="closeModal"
        class="fixed inset-0 z-40 {{ $modalVisible ? 'flex' : 'hidden' }} items-center justify-center bg-black/50 px-4 py-4 sm:py-8 backdrop-blur">
        <div class="relative w-full max-w-6xl rounded-2xl sm:rounded-3xl border border-green-300 bg-white p-4 sm:p-6 shadow-2xl max-h-[95vh] overflow-y-auto">
            <button type="button" class="absolute right-3 top-3 sm:right-4 sm:top-4 text-gray-400 hover:text-gray-900 transition text-xl sm:text-2xl"
                data-calendar-close wire:click="closeModal">
                ✕
            </button>
            <div class="space-y-3 sm:space-y-4">
                <div class="flex flex-col gap-1">
                    <p class="text-[10px] sm:text-xs uppercase tracking-[0.3em] sm:tracking-[0.4em] text-green-600">Detalle del día</p>
                    <h3 class="text-lg sm:text-xl md:text-2xl font-semibold pr-8 text-gray-900">
                        {{ $modalDateLabel ?? 'Selecciona un día con reservas' }}
                    </h3>
                    <p class="text-xs sm:text-sm text-gray-600">
                        @if ($modalDateLabel)
                            {{ count($modalReservations) }} reservas encontradas
                        @else
                            Haz clic sobre cualquier tarjeta resaltada para consultar sus reservas.
                        @endif
                    </p>
                </div>

                <div class="max-h-[420px] overflow-auto rounded-xl sm:rounded-2xl border border-gray-200 bg-gray-50">
                    <table class="w-full text-left text-xs sm:text-sm text-gray-700">
                        <thead class="sticky top-0 bg-green-600 text-[9px] sm:text-[11px] uppercase tracking-[0.2em] sm:tracking-[0.3em] text-white">
                            <tr>
                                <th class="px-2 py-2 sm:px-4 sm:py-3">Cancha</th>
                                <th class="px-2 py-2 sm:px-4 sm:py-3">Cliente</th>
                                <th class="px-2 py-2 sm:px-4 sm:py-3 hidden md:table-cell">Fecha reserva</th>
                                <th class="px-2 py-2 sm:px-4 sm:py-3">Inicio</th>
                                <th class="px-2 py-2 sm:px-4 sm:py-3 hidden sm:table-cell">Fin</th>
                                <th class="px-2 py-2 sm:px-4 sm:py-3 hidden lg:table-cell">Duración</th>
                                <th class="px-2 py-2 sm:px-4 sm:py-3 text-right">Total</th>
                                <th class="px-2 py-2 sm:px-4 sm:py-3 text-center hidden md:table-cell">Estado</th>
                                <th class="px-2 py-2 sm:px-4 sm:py-3 hidden lg:table-cell">Creado por</th>
                                <th class="px-2 py-2 sm:px-4 sm:py-3 hidden lg:table-cell">Actualizado por</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @php
                                $estadoColors = [
                                    'pendiente' => 'bg-amber-100 text-amber-700',
                                    'confirmada' => 'bg-green-100 text-green-700',
                                    'finalizada' => 'bg-blue-100 text-blue-700',
                                    'cancelada' => 'bg-red-100 text-red-700',
                                ];
                            @endphp
                            @forelse ($modalReservations as $reserva)
                                @php
                                    $estadoClass = $estadoColors[$reserva['estado'] ?? ''] ?? 'bg-gray-100 text-gray-700';
                                @endphp
                                <tr class="hover:bg-gray-100 transition-colors">
                                    <td class="px-2 py-2 sm:px-4 sm:py-3">
                                        <p class="font-semibold text-gray-900 text-xs sm:text-sm">{{ $reserva['cancha'] }}</p>
                                        <p class="text-[10px] sm:text-xs text-gray-500">#{{ $reserva['id'] }}</p>
                                    </td>
                                    <td class="px-2 py-2 sm:px-4 sm:py-3">
                                        <p class="font-semibold text-xs sm:text-sm text-gray-900">{{ $reserva['cliente'] }}</p>
                                        <p class="text-[10px] sm:text-xs text-gray-600">{{ $reserva['contacto'] }}</p>
                                    </td>
                                    <td class="px-2 py-2 sm:px-4 sm:py-3 text-gray-700 hidden md:table-cell">{{ $reserva['fecha_reserva'] }}</td>
                                    <td class="px-2 py-2 sm:px-4 sm:py-3 text-green-600 font-semibold text-xs sm:text-sm">{{ $reserva['inicio'] }}</td>
                                    <td class="px-2 py-2 sm:px-4 sm:py-3 text-red-600 font-semibold hidden sm:table-cell">{{ $reserva['fin'] }}</td>
                                    <td class="px-2 py-2 sm:px-4 sm:py-3 hidden lg:table-cell text-gray-700">{{ $reserva['duracion'] }}</td>
                                    <td class="px-2 py-2 sm:px-4 sm:py-3 text-right font-semibold text-green-600 text-xs sm:text-sm">
                                        {{ $reserva['total'] ? '$' . $reserva['total'] : '—' }}
                                    </td>
                                    <td class="px-2 py-2 sm:px-4 sm:py-3 text-center hidden md:table-cell">
                                        <span class="inline-flex rounded-full px-2 py-0.5 sm:px-3 sm:py-1 text-[9px] sm:text-[11px] font-semibold {{ $estadoClass }}">
                                            {{ $reserva['estado'] ? ucfirst($reserva['estado']) : 'Sin estado' }}
                                        </span>
                                    </td>
                                    <td class="px-2 py-2 sm:px-4 sm:py-3 hidden lg:table-cell">
                                        <p class="font-semibold text-gray-900">{{ $reserva['creador'] }}</p>
                                        <p class="text-xs text-gray-600">{{ $reserva['creador_email'] }}</p>
                                    </td>
                                    <td class="px-2 py-2 sm:px-4 sm:py-3 hidden lg:table-cell">
                                        <p class="font-semibold text-gray-900">{{ $reserva['actualizador'] }}</p>
                                        <p class="text-xs text-gray-600">{{ $reserva['actualizador_email'] }}</p>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="10" class="px-4 py-10 text-center text-gray-500">
                                        No hay reservas registradas para esta fecha.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
