<div class="space-y-8">
    <div class="rounded-3xl bg-gradient-to-r from-slate-900 via-slate-800 to-slate-900 p-8 shadow-2xl text-white">
        <div class="flex flex-col gap-6 md:flex-row md:items-center md:justify-between">
            <div class="space-y-3 max-w-2xl">
                <p class="text-sm uppercase tracking-[0.4em] text-emerald-200">Calendario</p>
                <h2 class="text-3xl font-bold">Agenda mensual inteligente</h2>
                <p class="text-slate-200 text-sm leading-relaxed">
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

        <div class="mt-8 grid gap-4 md:grid-cols-3 text-slate-100">
            <div class="rounded-2xl border border-emerald-500/30 bg-emerald-500/10 p-4">
                <p class="text-xs uppercase tracking-[0.4em] text-emerald-200">Reservas del mes</p>
                <p class="mt-2 text-3xl font-bold">{{ $totalMonthReservations }}</p>
                <p class="text-xs text-emerald-100">Registros en {{ $focusedMonthLabel }}</p>
            </div>
            <div class="rounded-2xl border border-cyan-500/30 bg-cyan-500/10 p-4">
                <p class="text-xs uppercase tracking-[0.4em] text-cyan-200">Días con reservas</p>
                <p class="mt-2 text-3xl font-bold">{{ $busyDaysCount }}</p>
                <p class="text-xs text-cyan-100">Fechas con al menos una reserva</p>
            </div>
            <div class="rounded-2xl border border-indigo-500/30 bg-indigo-500/10 p-4">
                <p class="text-xs uppercase tracking-[0.4em] text-indigo-200">Estado actual</p>
                <p class="mt-2 text-lg font-semibold">{{ $focusedMonthLabel }}</p>
                <p class="text-xs text-indigo-100">Zona horaria America/El_Salvador</p>
            </div>
        </div>
    </div>

    <div class="rounded-3xl border border-slate-800 bg-slate-950/80 p-6 shadow-2xl">
        <div class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
            <div>
                <h3 class="text-xl font-semibold text-white">Calendario del mes</h3>
                <p class="text-sm text-slate-400">
                    Selecciona cualquier día resaltado en verde para conocer las reservas registradas.
                </p>
            </div>
            <div class="flex items-center gap-3 text-xs text-slate-400">
                <span class="inline-flex items-center gap-2">
                    <span class="h-3 w-3 rounded-full bg-emerald-400/80"></span>Día con reservas
                </span>
                <span class="inline-flex items-center gap-2">
                    <span class="h-3 w-3 rounded-full border border-slate-500"></span>Día sin reservas
                </span>
            </div>
        </div>

        <div class="mt-6 grid grid-cols-7 text-center text-xs font-semibold uppercase tracking-widest text-slate-500">
            <span>Dom</span>
            <span>Lun</span>
            <span>Mar</span>
            <span>Mié</span>
            <span>Jue</span>
            <span>Vie</span>
            <span>Sáb</span>
        </div>

        <div class="relative mt-2">
            <div class="grid grid-cols-7 gap-2 transition opacity-100" wire:loading.class="opacity-40 pointer-events-none" wire:target="selectedPeriod">
                @foreach ($this->calendarDays as $day)
                    @php
                        $baseClasses = 'relative min-h-[90px] rounded-2xl border px-3 py-4 text-left transition-all';
                        $stateClasses = match (true) {
                            ! $day['isCurrentMonth'] => 'border-slate-900 bg-slate-900/30 text-slate-600',
                            $day['isToday'] => 'border-emerald-400 bg-emerald-500/10 text-white shadow-lg shadow-emerald-500/20',
                            default => 'border-slate-800 bg-slate-900/50 text-slate-300',
                        };
                        $interactivityClasses = $day['hasReservations']
                            ? 'cursor-pointer hover:-translate-y-1 hover:shadow-xl hover:shadow-emerald-500/15'
                            : 'cursor-not-allowed';
                    @endphp
                    <button type="button"
                        data-calendar-day
                        wire:key="day-{{ $day['date'] }}"
                        data-has-reservations="{{ $day['hasReservations'] ? 'true' : 'false' }}"
                        @if ($day['hasReservations']) wire:click="openDay('{{ $day['date'] }}')" @endif
                        @if (! $day['hasReservations']) disabled @endif
                        class="{{ $baseClasses }} {{ $stateClasses }} {{ $interactivityClasses }}">
                        <div class="flex items-start justify-between">
                            <span class="text-2xl font-semibold">{{ $day['label'] }}</span>
                            @if ($day['hasReservations'])
                                <span class="h-2 w-2 rounded-full bg-emerald-400"></span>
                            @else
                                <span class="h-2 w-2 rounded-full border border-slate-600/70"></span>
                            @endif
                        </div>
                        <p class="mt-1 text-xs text-slate-400">{{ \Carbon\Carbon::parse($day['date'])->isoFormat('DD MMM') }}</p>
                        @if ($day['hasReservations'])
                            <span class="mt-4 inline-flex items-center gap-2 rounded-full bg-emerald-500/15 px-3 py-1 text-[11px] font-semibold text-emerald-100">
                                <span class="h-2 w-2 animate-pulse rounded-full bg-emerald-300"></span>
                                {{ $day['reservationsCount'] }} reservas
                            </span>
                        @else
                            <span class="mt-4 inline-flex items-center gap-2 rounded-full border border-slate-700 px-3 py-1 text-[11px] font-semibold text-slate-500">
                                <span class="h-2 w-2 rounded-full border border-slate-500/70"></span>
                                Día libre
                            </span>
                        @endif
                    </button>
                @endforeach
            </div>
            <div wire:loading.flex wire:target="selectedPeriod"
                class="pointer-events-none absolute inset-0 items-center justify-center rounded-3xl bg-slate-950/60 text-slate-200">
                <div class="flex flex-col items-center gap-3">
                    <svg class="h-8 w-8 animate-spin text-emerald-300" xmlns="http://www.w3.org/2000/svg" fill="none"
                        viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4l3-3-3-3v4a8 8 0 00-8 8h4z" />
                    </svg>
                    <p class="text-sm uppercase tracking-[0.3em] text-emerald-200">Actualizando mes</p>
                </div>
            </div>
        </div>
    </div>

    <div data-calendar-modal wire:click.self="closeModal"
        class="fixed inset-0 z-40 {{ $modalVisible ? 'flex' : 'hidden' }} items-center justify-center bg-slate-950/80 px-4 py-8 backdrop-blur">
        <div class="relative w-full max-w-6xl rounded-3xl border border-slate-800 bg-slate-950/95 p-6 text-white shadow-2xl">
            <button type="button" class="absolute right-4 top-4 text-slate-400 hover:text-white transition"
                data-calendar-close wire:click="closeModal">
                ✕
            </button>
            <div class="space-y-4">
                <div class="flex flex-col gap-1">
                    <p class="text-xs uppercase tracking-[0.4em] text-emerald-200">Detalle del día</p>
                    <h3 class="text-2xl font-semibold">
                        {{ $modalDateLabel ?? 'Selecciona un día con reservas' }}
                    </h3>
                    <p class="text-sm text-slate-400">
                        @if ($modalDateLabel)
                            {{ count($modalReservations) }} reservas encontradas
                        @else
                            Haz clic sobre cualquier tarjeta resaltada para consultar sus reservas.
                        @endif
                    </p>
                </div>

                <div class="max-h-[420px] overflow-auto rounded-2xl border border-slate-900 bg-slate-950/60">
                    <table class="w-full text-left text-sm text-slate-200">
                        <thead class="sticky top-0 bg-slate-900/90 text-[11px] uppercase tracking-[0.3em] text-slate-500">
                            <tr>
                                <th class="px-4 py-3">Cancha</th>
                                <th class="px-4 py-3">Cliente</th>
                                <th class="px-4 py-3">Fecha reserva</th>
                                <th class="px-4 py-3">Inicio</th>
                                <th class="px-4 py-3">Fin</th>
                                <th class="px-4 py-3">Duración</th>
                                <th class="px-4 py-3 text-right">Total</th>
                                <th class="px-4 py-3 text-center">Estado</th>
                                <th class="px-4 py-3">Creado por</th>
                                <th class="px-4 py-3">Actualizado por</th>
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
                                    <td class="px-4 py-3">
                                        <p class="font-semibold text-white">{{ $reserva['cancha'] }}</p>
                                        <p class="text-xs text-slate-500">#{{ $reserva['id'] }}</p>
                                    </td>
                                    <td class="px-4 py-3">
                                        <p class="font-semibold">{{ $reserva['cliente'] }}</p>
                                        <p class="text-xs text-slate-400">{{ $reserva['contacto'] }}</p>
                                    </td>
                                    <td class="px-4 py-3 text-slate-300">{{ $reserva['fecha_reserva'] }}</td>
                                    <td class="px-4 py-3 text-emerald-300">{{ $reserva['inicio'] }}</td>
                                    <td class="px-4 py-3 text-rose-200">{{ $reserva['fin'] }}</td>
                                    <td class="px-4 py-3">{{ $reserva['duracion'] }}</td>
                                    <td class="px-4 py-3 text-right font-semibold text-emerald-400">
                                        {{ $reserva['total'] ? '$' . $reserva['total'] : '—' }}
                                    </td>
                                    <td class="px-4 py-3 text-center">
                                        <span class="inline-flex rounded-full px-3 py-1 text-[11px] font-semibold {{ $estadoClass }}">
                                            {{ $reserva['estado'] ? ucfirst($reserva['estado']) : 'Sin estado' }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3">
                                        <p class="font-semibold">{{ $reserva['creador'] }}</p>
                                        <p class="text-xs text-slate-400">{{ $reserva['creador_email'] }}</p>
                                    </td>
                                    <td class="px-4 py-3">
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
