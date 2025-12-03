<?php

namespace App\Livewire;

use App\Models\Reserva;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Livewire\Component;

class AdminCalendar extends Component
{
    /**
     * Mes seleccionado en formato YYYY-MM.
     */
    public string $selectedPeriod;

    /**
     * Opciones del selector de mes.
     *
     * @var array<string, string>
     */
    public array $monthOptions = [];

    /**
     * Reservas agrupadas por fecha (YYYY-MM-DD).
     *
     * @var array<string, array<int, array<string, mixed>>>
     */
    public array $reservationsByDate = [];

    /**
     * Reservas mostradas en el modal.
     *
     * @var array<int, array<string, mixed>>
     */
    public array $modalReservations = [];

    /**
     * Texto descriptivo de la fecha seleccionada.
     */
    public ?string $modalDateLabel = null;

    /**
     * Controla la visualización del modal.
     */
    public bool $modalVisible = false;

    /**
     * Totales de ayuda para el encabezado.
     */
    public int $totalMonthReservations = 0;
    public int $busyDaysCount = 0;

    /**
     * Escucha eventos emitidos desde JavaScript.
     *
     * @var list<string>
     */
    protected $listeners = [
        'admin-calendar-close' => 'closeModal',
    ];

    public function mount(): void
    {
        $now = Carbon::now('America/El_Salvador');
        $this->selectedPeriod = $now->format('Y-m');
        $this->monthOptions = $this->buildMonthOptions($now);
        $this->loadReservations();
    }

    public function updatedSelectedPeriod(): void
    {
        if (! array_key_exists($this->selectedPeriod, $this->monthOptions)) {
            $this->selectedPeriod = array_key_first($this->monthOptions) ?? Carbon::now('America/El_Salvador')->format('Y-m');
        }

        $this->loadReservations();
    }

    public function openDay(string $date): void
    {
        if (! isset($this->reservationsByDate[$date])) {
            return;
        }

        $this->modalReservations = $this->reservationsByDate[$date];
        $this->modalDateLabel = Carbon::parse($date)
            ->locale('es')
            ->isoFormat('dddd D [de] MMMM [de] YYYY');
        $this->modalVisible = true;
        $this->dispatch('admin-calendar:modal-visible', open: true);
    }

    public function closeModal(): void
    {
        $this->modalVisible = false;
        $this->dispatch('admin-calendar:modal-visible', open: false);
    }

    public function getCalendarDaysProperty(): array
    {
        $focusedMonth = $this->focusedMonth();
        $start = $focusedMonth->copy()->startOfWeek(Carbon::SUNDAY);
        $end = $focusedMonth->copy()->endOfMonth()->endOfWeek(Carbon::SATURDAY);

        $days = [];
        for ($day = $start->copy(); $day <= $end; $day->addDay()) {
            $dateString = $day->toDateString();
            $hasReservations = isset($this->reservationsByDate[$dateString]);

            $days[] = [
                'date' => $dateString,
                'label' => $day->day,
                'isCurrentMonth' => $day->month === $focusedMonth->month,
                'isToday' => $day->isToday(),
                'hasReservations' => $hasReservations,
                'reservationsCount' => $hasReservations ? count($this->reservationsByDate[$dateString]) : 0,
            ];
        }

        return $days;
    }

    public function render()
    {
        return view('livewire.admin-calendar', [
            'focusedMonthLabel' => $this->focusedMonth()
                ->locale('es')
                ->isoFormat('MMMM YYYY'),
        ]);
    }

    private function loadReservations(): void
    {
        $startOfMonth = $this->focusedMonth()->copy()->startOfMonth();
        $endOfMonth = $this->focusedMonth()->copy()->endOfMonth()->endOfDay();

        $reservas = Reserva::with(['cancha', 'cliente', 'creador', 'actualizador'])
            ->where(function ($query) use ($startOfMonth, $endOfMonth) {
                $query->whereBetween('fecha_inicio', [$startOfMonth, $endOfMonth])
                    ->orWhereBetween('fecha_reserva', [$startOfMonth, $endOfMonth]);
            })
            ->orderBy('fecha_inicio')
            ->get();

        $this->reservationsByDate = $reservas
            ->groupBy(fn (Reserva $reserva) => $this->resolveDateKey($reserva))
            ->map(fn ($items) => $items->map(fn (Reserva $reserva) => $this->mapReserva($reserva))->values()->all())
            ->toArray();

        $this->totalMonthReservations = $reservas->count();
        $this->busyDaysCount = count($this->reservationsByDate);
        $this->modalReservations = [];
        $this->modalDateLabel = null;
        $this->modalVisible = false;
        $this->dispatch('admin-calendar:modal-visible', open: false);
    }

    private function buildMonthOptions(Carbon $now): array
    {
        $start = $now->copy()->startOfMonth()->subMonths(2);
        $end = $now->copy()->startOfMonth()->addMonths(9);

        $options = [];
        for ($cursor = $start->copy(); $cursor <= $end; $cursor->addMonth()) {
            $options[$cursor->format('Y-m')] = Str::ucfirst(
                $cursor->locale('es')->isoFormat('MMMM YYYY')
            );
        }

        return $options;
    }

    private function focusedMonth(): Carbon
    {
        return Carbon::createFromFormat('Y-m', $this->selectedPeriod, 'America/El_Salvador')
            ->startOfMonth();
    }

    private function resolveDateKey(Reserva $reserva): string
    {
        $fecha = $reserva->fecha_inicio ?? $reserva->fecha_reserva ?? $reserva->created_at;

        return Carbon::parse($fecha)->toDateString();
    }

    /**
     * Mapea la información necesaria para el modal.
     *
     * @return array<string, mixed>
     */
    private function mapReserva(Reserva $reserva): array
    {
        return [
            'id' => $reserva->id,
            'cancha' => optional($reserva->cancha)->nombre ?? 'Cancha no disponible',
            'cliente' => optional($reserva->cliente)->nombre ?? 'Cliente no disponible',
            'contacto' => optional($reserva->cliente)->telefono
                ?? optional($reserva->cliente)->email
                ?? '—',
            'fecha_reserva' => optional($reserva->fecha_reserva)?->format('d/m/Y') ?? '—',
            'inicio' => optional($reserva->fecha_inicio)?->format('d/m/Y H:i') ?? '—',
            'fin' => optional($reserva->fecha_fin)?->format('d/m/Y H:i') ?? '—',
            'duracion' => ! is_null($reserva->duracion_minutos) ? $reserva->duracion_minutos . ' min' : '—',
            'total' => ! is_null($reserva->total) ? number_format((float) $reserva->total, 2, '.', ',') : null,
            'estado' => $reserva->estado,
            'creador' => optional($reserva->creador)->nombre ?? '—',
            'creador_email' => optional($reserva->creador)->email ?? '—',
            'actualizador' => optional($reserva->actualizador)->nombre ?? '—',
            'actualizador_email' => optional($reserva->actualizador)->email ?? '—',
        ];
    }
}
