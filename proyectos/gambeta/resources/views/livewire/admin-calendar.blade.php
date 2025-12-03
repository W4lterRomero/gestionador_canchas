<div class="space-y-4 sm:space-y-8">
    <div class="rounded-2xl sm:rounded-3xl bg-gradient-to-r from-slate-900 via-slate-800 to-slate-900 p-4 sm:p-6 md:p-8 shadow-2xl text-white">
        <div class="flex flex-col gap-4 sm:gap-6 md:flex-row md:items-center md:justify-between">
            <div class="space-y-2 sm:space-y-3 max-w-2xl">
                <p class="text-xs sm:text-sm uppercase tracking-[0.3em] sm:tracking-[0.4em] text-emerald-200">Calendario</p>
                <h2 class="text-xl sm:text-2xl md:text-3xl font-bold">Agenda mensual inteligente</h2>
                <p class="text-slate-200 text-xs sm:text-sm leading-relaxed">
                    Visualiza en un solo golpe de vista qué días tienen reservas confirmadas y accede al detalle
                    de cada jornada sin salir del panel.
                </p>
            </div>
            <div class="w-full md:w-auto">
                <label class="text-xs uppercase tracking-widest text-emerald-200">Visualizar mes</label>
                <div class="mt-2 rounded-2xl bg-white/10 p-3 backdrop-blur">
                    <select wire:model.live="selectedPeriod" wire:loading.attr="disabled"
                        class="w-full bg-transparent text-white font-semibold focus:outline-none">
                        @foreach ($monthOptions as $value => $label)
                            <option class="text-slate-900" value="{{ $value }}">{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        <div class="mt-4 sm:mt-6 md:mt-8 grid gap-3 sm:gap-4 grid-cols-2 md:grid-cols-3 text-slate-100">
            <div class="rounded-xl sm:rounded-2xl border border-emerald-500/30 bg-emerald-500/10 p-3 sm:p-4">
                <p class="text-[10px] sm:text-xs uppercase tracking-[0.3em] sm:tracking-[0.4em] text-emerald-200">Reservas</p>
                <p class="mt-1 sm:mt-2 text-2xl sm:text-3xl font-bold">{{ $totalMonthReservations }}</p>
                <p class="text-[10px] sm:text-xs text-emerald-100 truncate">Registros en {{ $focusedMonthLabel }}</p>
            </div>
            <div class="rounded-xl sm:rounded-2xl border border-cyan-500/30 bg-cyan-500/10 p-3 sm:p-4">
                <p class="text-[10px] sm:text-xs uppercase tracking-[0.3em] sm:tracking-[0.4em] text-cyan-200">Días activos</p>
                <p class="mt-1 sm:mt-2 text-2xl sm:text-3xl font-bold">{{ $busyDaysCount }}</p>
                <p class="text-[10px] sm:text-xs text-cyan-100">Con reservas</p>
            </div>
            <div class="rounded-xl sm:rounded-2xl border border-indigo-500/30 bg-indigo-500/10 p-3 sm:p-4 col-span-2 md:col-span-1">
                <p class="text-[10px] sm:text-xs uppercase tracking-[0.3em] sm:tracking-[0.4em] text-indigo-200">Estado actual</p>
                <p class="mt-1 sm:mt-2 text-base sm:text-lg font-semibold">{{ $focusedMonthLabel }}</p>
                <p class="text-[10px] sm:text-xs text-indigo-100">Zona horaria America/El_Salvador</p>
            </div>
        </div>
    </div>

    <div class="rounded-2xl sm:rounded-3xl border border-slate-800 bg-slate-950/80 p-3 sm:p-4 md:p-6 shadow-2xl">
        <div class="flex flex-col gap-2 sm:gap-3 md:flex-row md:items-center md:justify-between">
            <div>
                <h3 class="text-base sm:text-lg md:text-xl font-semibold text-white">Calendario del mes</h3>
                <p class="text-xs sm:text-sm text-slate-400">
                    Selecciona cualquier día resaltado en verde para conocer las reservas registradas.
                </p>
            </div>
            <div class="flex items-center gap-2 sm:gap-3 text-[10px] sm:text-xs text-slate-400 flex-wrap">
                <span class="inline-flex items-center gap-1.5 sm:gap-2">
                    <span class="h-2.5 w-2.5 sm:h-3 sm:w-3 rounded-full bg-emerald-400/80"></span>Con reservas
                </span>
                <span class="inline-flex items-center gap-1.5 sm:gap-2">
                    <span class="h-2.5 w-2.5 sm:h-3 sm:w-3 rounded-full border border-slate-500"></span>Sin reservas
                </span>
            </div>
        </div>

        <div class="mt-4 sm:mt-6 grid grid-cols-7 text-center text-[10px] sm:text-xs font-semibold uppercase tracking-wider sm:tracking-widest text-slate-500">
            <span class="hidden sm:inline">Dom</span><span class="sm:hidden">D</span>
            <span class="hidden sm:inline">Lun</span><span class="sm:hidden">L</span>
            <span class="hidden sm:inline">Mar</span><span class="sm:hidden">M</span>
            <span class="hidden sm:inline">Mié</span><span class="sm:hidden">X</span>
            <span class="hidden sm:inline">Jue</span><span class="sm:hidden">J</span>
            <span class="hidden sm:inline">Vie</span><span class="sm:hidden">V</span>
            <span class="hidden sm:inline">Sáb</span><span class="sm:hidden">S</span>
        </div>
        <div class="mt-6 flex items-center justify-between text-[11px] font-semibold uppercase tracking-[0.4em] text-slate-500 md:hidden">
            <span>Días del mes</span>
            <span>{{ $focusedMonthLabel }}</span>
        </div>

        <div class="mt-2 grid grid-cols-7 gap-1 sm:gap-2">
            @foreach ($this->calendarDays as $day)
                @php
                    $baseClasses = 'relative min-h-[60px] sm:min-h-[75px] md:min-h-[90px] rounded-lg sm:rounded-xl md:rounded-2xl border px-1.5 py-2 sm:px-2 sm:py-3 md:px-3 md:py-4 text-left transition-all';
                    $stateClasses = match (true) {
                        ! $day['isCurrentMonth'] => 'border-slate-900 bg-slate-900/30 text-slate-600',
                        $day['isToday'] => 'border-emerald-400 bg-emerald-500/10 text-white shadow-lg shadow-emerald-500/20',
                        default => 'border-slate-800 bg-slate-900/50 text-slate-300',
                    };
                    $interactivityClasses = $day['hasReservations']
                        ? 'cursor-pointer hover:-translate-y-1 hover:shadow-xl hover:shadow-emerald-500/15'
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
                            <span class="h-1.5 w-1.5 sm:h-2 sm:w-2 rounded-full bg-emerald-400"></span>
                        @endif
                    </div>
                    <p class="mt-0.5 sm:mt-1 text-[9px] sm:text-xs text-slate-400 hidden sm:block">{{ \Carbon\Carbon::parse($day['date'])->isoFormat('DD MMM') }}</p>
                    @if ($day['hasReservations'])
                        <span class="mt-1 sm:mt-2 md:mt-4 inline-flex items-center gap-1 sm:gap-2 rounded-full bg-emerald-500/15 px-1.5 py-0.5 sm:px-2 sm:py-1 md:px-3 text-[9px] sm:text-[10px] md:text-[11px] font-semibold text-emerald-100">
                            <span class="h-1.5 w-1.5 sm:h-2 sm:w-2 animate-pulse rounded-full bg-emerald-300 hidden sm:inline"></span>
                            <span class="sm:hidden">{{ $day['reservationsCount'] }}</span>
                            <span class="hidden sm:inline">{{ $day['reservationsCount'] }} reservas</span>
                        </span>
                    @else
                        <span class="mt-1 sm:mt-2 md:mt-4 inline-flex items-center rounded-full border border-slate-700 px-1.5 py-0.5 sm:px-2 sm:py-1 md:px-3 text-[9px] sm:text-[10px] md:text-[11px] font-semibold text-slate-500 hidden sm:inline-flex">
                            Libre
                        </span>
                    @endif
                </button>
            @endforeach
        </div>
    </div>

    <div data-calendar-modal wire:click.self="closeModal"
        class="fixed inset-0 z-40 {{ $modalVisible ? 'flex' : 'hidden' }} items-center justify-center bg-slate-950/80 px-4 py-4 sm:py-8 backdrop-blur">
        <div class="relative w-full max-w-6xl rounded-2xl sm:rounded-3xl border border-slate-800 bg-slate-950/95 p-4 sm:p-6 text-white shadow-2xl max-h-[95vh] overflow-y-auto">
            <button type="button" class="absolute right-3 top-3 sm:right-4 sm:top-4 text-slate-400 hover:text-white transition text-xl sm:text-2xl"
                data-calendar-close wire:click="closeModal">
                ✕
            </button>
            <div class="space-y-3 sm:space-y-4">
                <div class="flex flex-col gap-1">
                    <p class="text-[10px] sm:text-xs uppercase tracking-[0.3em] sm:tracking-[0.4em] text-emerald-200">Detalle del día</p>
                    <h3 class="text-lg sm:text-xl md:text-2xl font-semibold pr-8">
                        {{ $modalDateLabel ?? 'Selecciona un día con reservas' }}
                    </h3>
                    <p class="text-xs sm:text-sm text-slate-400">
                        @if ($modalDateLabel)
                            {{ count($modalReservations) }} reservas encontradas
                        @else
                            Haz clic sobre cualquier tarjeta resaltada para consultar sus reservas.
                        @endif
                    </p>
                </div>

                <div class="max-h-[420px] overflow-auto rounded-xl sm:rounded-2xl border border-slate-900 bg-slate-950/60">
                    <table class="w-full text-left text-xs sm:text-sm text-slate-200">
                        <thead class="sticky top-0 bg-slate-900/90 text-[9px] sm:text-[11px] uppercase tracking-[0.2em] sm:tracking-[0.3em] text-slate-500">
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
                        <tbody class="divide-y divide-slate-900">
                            @php
                                $estadoColors = [
                                    'pendiente' => 'bg-amber-500/15 text-amber-300',
                                    'confirmada' => 'bg-emerald-500/15 text-emerald-300',
                                    'finalizada' => 'bg-blue-500/15 text-blue-300',
                                    'cancelada' => 'bg-rose-500/15 text-rose-300',
                                ];
                            @endphp
                            @forelse ($modalReservations as $reserva)
                                @php
                                    $estadoClass = $estadoColors[$reserva['estado'] ?? ''] ?? 'bg-slate-600/20 text-slate-200';
                                @endphp
                                <tr class="hover:bg-slate-900/60 transition-colors">
                                    <td class="px-2 py-2 sm:px-4 sm:py-3">
                                        <p class="font-semibold text-white text-xs sm:text-sm">{{ $reserva['cancha'] }}</p>
                                        <p class="text-[10px] sm:text-xs text-slate-500">#{{ $reserva['id'] }}</p>
                                    </td>
                                    <td class="px-2 py-2 sm:px-4 sm:py-3">
                                        <p class="font-semibold text-xs sm:text-sm">{{ $reserva['cliente'] }}</p>
                                        <p class="text-[10px] sm:text-xs text-slate-400">{{ $reserva['contacto'] }}</p>
                                    </td>
                                    <td class="px-2 py-2 sm:px-4 sm:py-3 text-slate-300 hidden md:table-cell">{{ $reserva['fecha_reserva'] }}</td>
                                    <td class="px-2 py-2 sm:px-4 sm:py-3 text-emerald-300 text-xs sm:text-sm">{{ $reserva['inicio'] }}</td>
                                    <td class="px-2 py-2 sm:px-4 sm:py-3 text-rose-200 hidden sm:table-cell">{{ $reserva['fin'] }}</td>
                                    <td class="px-2 py-2 sm:px-4 sm:py-3 hidden lg:table-cell">{{ $reserva['duracion'] }}</td>
                                    <td class="px-2 py-2 sm:px-4 sm:py-3 text-right font-semibold text-emerald-400 text-xs sm:text-sm">
                                        {{ $reserva['total'] ? '$' . $reserva['total'] : '—' }}
                                    </td>
                                    <td class="px-2 py-2 sm:px-4 sm:py-3 text-center hidden md:table-cell">
                                        <span class="inline-flex rounded-full px-2 py-0.5 sm:px-3 sm:py-1 text-[9px] sm:text-[11px] font-semibold {{ $estadoClass }}">
                                            {{ $reserva['estado'] ? ucfirst($reserva['estado']) : 'Sin estado' }}
                                        </span>
                                    </td>
                                    <td class="px-2 py-2 sm:px-4 sm:py-3 hidden lg:table-cell">
                                        <p class="font-semibold">{{ $reserva['creador'] }}</p>
                                        <p class="text-xs text-slate-400">{{ $reserva['creador_email'] }}</p>
                                    </td>
                                    <td class="px-2 py-2 sm:px-4 sm:py-3 hidden lg:table-cell">
                                        <p class="font-semibold">{{ $reserva['actualizador'] }}</p>
                                        <p class="text-xs text-slate-400">{{ $reserva['actualizador_email'] }}</p>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="10" class="px-4 py-10 text-center text-slate-500">
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
