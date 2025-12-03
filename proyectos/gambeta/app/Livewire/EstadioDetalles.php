<?php

namespace App\Livewire;

use App\Models\Cancha;
use App\Models\CanchaImagen;
use App\Models\Reserva;
use Livewire\Component;
use Illuminate\Support\Facades\Storage;

class EstadioDetalles extends Component
{
    // Public properties are serializable
    public $canchaId;

    // Calendar state
    public $mesActual;
    public $añoActual;

    // Modal state
    public $diaSeleccionado = null;
    public $mostrarModal = false;

    public function mount($id)
    {
        $this->canchaId = $id;

        // Ensure stadium exists
        if (!Cancha::find($id)) {
            abort(404, 'Cancha no encontrada');
        }

        // Start calendar at current month
        $this->mesActual = now()->month;
        $this->añoActual = now()->year;
    }

    public function mesAnterior()
    {
        $this->mesActual--;
        if ($this->mesActual < 1) {
            $this->mesActual = 12;
            $this->añoActual--;
        }
    }

    public function mesSiguiente()
    {
        $this->mesActual++;
        if ($this->mesActual > 12) {
            $this->mesActual = 1;
            $this->añoActual++;
        }
    }

    public function mesHoy()
    {
        $this->mesActual = now()->month;
        $this->añoActual = now()->year;
    }

    public function verDetallesDia($dia)
    {
        $this->diaSeleccionado = \Carbon\Carbon::create($this->añoActual, $this->mesActual, $dia);
        $this->mostrarModal = true;
    }

    public function cerrarModal()
    {
        $this->mostrarModal = false;
        $this->diaSeleccionado = null;
    }

    public function getReservasDiaSeleccionadoProperty()
    {
        if (!$this->diaSeleccionado) return collect();

        return Reserva::where('cancha_id', $this->canchaId)
            ->with('cliente')
            ->whereDate('fecha_inicio', $this->diaSeleccionado)
            ->orderBy('fecha_inicio', 'asc')
            ->get();
    }

    // Generate 30-min time slots (8 AM - 10 PM)
    public function getBloquesHorariosProperty()
    {
        $bloques = [];
        $horaInicio = 8; // 8 AM
        $horaFin = 22; // 10 PM

        for ($hora = $horaInicio; $hora < $horaFin; $hora++) {
            $bloques[] = sprintf('%02d:00', $hora);
            $bloques[] = sprintf('%02d:30', $hora);
        }

        return $bloques;
    }

    // Check if slot is booked
    public function getBloqueConReserva($horaBloque)
    {
        if (!$this->diaSeleccionado) return null;

        list($hora, $minuto) = explode(':', $horaBloque);
        $inicioBloque = $this->diaSeleccionado->copy()->setTime($hora, $minuto, 0);
        $finBloque = $inicioBloque->copy()->addMinutes(30);

        foreach ($this->reservasDiaSeleccionado as $reserva) {
            // Check for overlap
            if ($reserva->fecha_inicio < $finBloque && $reserva->fecha_fin > $inicioBloque) {
                return $reserva;
            }
        }

        return null;
    }

    // Calculate slot span
    public function calcularBloques($reserva)
    {
        $duracionMinutos = $reserva->duracion_minutos ?? $reserva->fecha_inicio->diffInMinutes($reserva->fecha_fin);
        return ceil($duracionMinutos / 30);
    }

    // Check if this is the start block
    public function esInicioReserva($reserva, $horaBloque)
    {
        list($hora, $minuto) = explode(':', $horaBloque);
        $inicioBloque = $this->diaSeleccionado->copy()->setTime($hora, $minuto, 0);

        // Is start time in this block?
        return $reserva->fecha_inicio >= $inicioBloque &&
               $reserva->fecha_inicio < $inicioBloque->copy()->addMinutes(30);
    }

    public function getCanchaProperty()
    {
        return Cancha::findOrFail($this->canchaId);
    }

    public function getReservasProperty()
    {
        $primerDia = \Carbon\Carbon::create($this->añoActual, $this->mesActual, 1)->startOfDay();
        $ultimoDia = $primerDia->copy()->endOfMonth()->endOfDay();

        return Reserva::where('cancha_id', $this->canchaId)
            ->with('cliente')
            ->whereBetween('fecha_inicio', [$primerDia, $ultimoDia])
            ->orderBy('fecha_inicio', 'asc')
            ->get();
    }

    public function getDiasCalendarioProperty()
    {
        $primerDia = \Carbon\Carbon::create($this->añoActual, $this->mesActual, 1);
        $ultimoDia = $primerDia->copy()->endOfMonth();

        // Get starting weekday
        $primerDiaSemana = $primerDia->dayOfWeek;

        // Adjust for Monday start
        $primerDiaSemana = $primerDiaSemana == 0 ? 6 : $primerDiaSemana - 1;

        $dias = [];

        // Empty slots before 1st of month
        for ($i = 0; $i < $primerDiaSemana; $i++) {
            $dias[] = null;
        }

        // Fill actual days
        for ($dia = 1; $dia <= $ultimoDia->day; $dia++) {
            $dias[] = \Carbon\Carbon::create($this->añoActual, $this->mesActual, $dia);
        }

        return $dias;
    }

    // Filter reservations by date
    public function getReservasPorFecha($fecha)
    {
        if (!$fecha) return collect();

        return $this->reservas->filter(function($reserva) use ($fecha) {
            return $reserva->fecha_inicio->isSameDay($fecha);
        });
    }

    // Month name helper
    public function getNombreMesProperty()
    {
        $meses = [
            1 => 'Enero', 2 => 'Febrero', 3 => 'Marzo', 4 => 'Abril',
            5 => 'Mayo', 6 => 'Junio', 7 => 'Julio', 8 => 'Agosto',
            9 => 'Septiembre', 10 => 'Octubre', 11 => 'Noviembre', 12 => 'Diciembre'
        ];
        return $meses[$this->mesActual];
    }

    public function render()
    {
        return view('livewire.estadio-detalles')->layout('components.layouts.app');
    }
}
