<?php

namespace App\Livewire;

use App\Models\BloqueoHorario;
use App\Models\Reserva;
use Carbon\Carbon;
use Livewire\Component;

class CalendarioReserva extends Component
{
    public $mes;
    public $anio;

    public $fecha;
    public $hora;
    public $canchaId;

    public $horasDisponibles = [];

    public function mount($fechaInicial = null)
    {
        $hoy = Carbon::now('America/El_Salvador');

        $this->fecha = $fechaInicial ?? $hoy->format('Y-m-d');
        $this->mes   = Carbon::parse($this->fecha)->month;
        $this->anio  = Carbon::parse($this->fecha)->year;

        $this->generarHoras();
        $this->dispatch('fechaSeleccionada', fecha: $this->fecha);
    }

    public function cambiarMes($direccion)
    {
        $nuevoMes = Carbon::create($this->anio, $this->mes, 1)->addMonths($direccion);

        if ($nuevoMes->isPast() && !$nuevoMes->isSameMonth(Carbon::now('America/El_Salvador'))) {
            return;
        }

        $this->mes  = $nuevoMes->month;
        $this->anio = $nuevoMes->year;
    }

public function seleccionarDia($dia)
{
    $fechaSeleccionada = Carbon::create($this->anio, $this->mes, $dia);

    if ($fechaSeleccionada->isPast() && !$fechaSeleccionada->isToday()) {
        return;
    }

    $this->fecha = $fechaSeleccionada->format('Y-m-d');

    $this->generarHoras();
    $this->dispatch('fechaSeleccionada', fecha: $this->fecha);
}


    public function seleccionarHora($hora)
    {
        $item = collect($this->horasDisponibles)->firstWhere('hora', $hora);

        if ($item && $item['ocupada']) {
            return;
        }

        $this->hora = $hora;

        $this->dispatch('horaSeleccionada', hora: $hora);
    }

    private function generarHoras()
    {
        if (! $this->canchaId) {
            $this->horasDisponibles = [];
            return;
        }

        $todas = [];

        $inicio = Carbon::createFromTime(0, 0, 0, 'America/El_Salvador');
        $fin    = Carbon::createFromTime(23, 30, 0, 'America/El_Salvador');

        while ($inicio <= $fin) {
            $todas[] = $inicio->format('H:i');
            $inicio->addMinutes(30);
        }

        $inicioDia = Carbon::parse($this->fecha, 'America/El_Salvador')->startOfDay();
        $finDia = $inicioDia->copy()->endOfDay();

        $reservas = Reserva::where('cancha_id', $this->canchaId)
            ->where('fecha_inicio', '<', $finDia)
            ->where('fecha_fin', '>', $inicioDia)
            ->get(['fecha_inicio', 'fecha_fin']);

        $bloqueos = BloqueoHorario::where('cancha_id', $this->canchaId)
            ->where('fecha_inicio', '<', $finDia)
            ->where('fecha_fin', '>', $inicioDia)
            ->get(['fecha_inicio', 'fecha_fin']);

        $bloqueadasReservas = $this->generarSlotsBloqueados($reservas, $inicioDia, $finDia);
        $bloqueadasAdmin = $this->generarSlotsBloqueados($bloqueos, $inicioDia, $finDia);

        $bloqueadas = array_values(array_unique(array_merge($bloqueadasReservas, $bloqueadasAdmin)));

        $ahora = Carbon::now('America/El_Salvador');
        $esHoy = $this->fecha === $ahora->format('Y-m-d');
        $fechaActual = $this->fecha;

        $this->horasDisponibles = collect($todas)
            ->map(function ($h) use ($bloqueadas, $esHoy, $ahora, $fechaActual) {
                $isPastHour = false;

                if ($esHoy) {
                    $horaCompleta = Carbon::createFromFormat(
                        'Y-m-d H:i',
                        $fechaActual . ' ' . $h,
                        'America/El_Salvador'
                    );
                    $isPastHour = $horaCompleta->lessThanOrEqualTo($ahora);
                }

                return [
                    'hora'    => $h,
                    'ocupada' => in_array($h, $bloqueadas) || $isPastHour,
                ];
            })
            ->toArray();
    }

    /**
     * Devuelve los slots de 30 minutos ocupados dentro del día.
     *
     * @param iterable<int, object> $registros
     * @return list<string>
     */
    private function generarSlotsBloqueados(iterable $registros, Carbon $inicioDia, Carbon $finDia): array
    {
        $slots = [];

        foreach ($registros as $registro) {
            $ini = Carbon::parse($registro->fecha_inicio, 'America/El_Salvador')
                ->copy()
                ->setSecond(0);
            $fin = Carbon::parse($registro->fecha_fin, 'America/El_Salvador')
                ->copy()
                ->setSecond(0);

            if ($ini->lt($inicioDia)) {
                $ini = $inicioDia->copy();
            }

            if ($fin->gt($finDia)) {
                $fin = $finDia->copy();
            }

            while ($ini < $fin) {
                $slots[] = $ini->format('H:i');
                $ini->addMinutes(30);
            }
        }

        return $slots;
    }

    public function render()
    {
        return view('livewire.calendario-reserva');
    }
}
