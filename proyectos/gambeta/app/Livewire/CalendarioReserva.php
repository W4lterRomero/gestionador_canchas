<?php

namespace App\Livewire;

use Carbon\Carbon;
use Livewire\Component;
use App\Models\Reserva;

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

    // ESTA ES LA REGLA CORRECTA 👇
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
        $todas = [];

        $inicio = Carbon::createFromTime(0, 0, 0, 'America/El_Salvador');
        $fin    = Carbon::createFromTime(23, 30, 0, 'America/El_Salvador');

        while ($inicio <= $fin) {
            $todas[] = $inicio->format('H:i');
            $inicio->addMinutes(30);
        }

        $reservas = Reserva::where('cancha_id', $this->canchaId)
            ->whereDate('fecha_inicio', $this->fecha)
            ->get();

        $bloqueadas = [];

        foreach ($reservas as $r) {
            $ini = Carbon::parse($r->fecha_inicio);
            $fin = Carbon::parse($r->fecha_fin);

            while ($ini < $fin) {
                $bloqueadas[] = $ini->format('H:i');
                $ini->addMinutes(30);
            }
        }

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

    public function render()
    {
        return view('livewire.calendario-reserva');
    }
}
