<?php

namespace App\Livewire;

use App\Models\Cancha;
use App\Models\Reserva;
use Livewire\Component;

class EstadioDetalles extends Component
{
    // (serializable)
    public $canchaId;

    // Para el calendario
    public $mesActual;
    public $añoActual;

    // Para el modal de detalles
    public $diaSeleccionado = null;
    public $mostrarModal = false;

    public function mount($id)
    {
        $this->canchaId = $id;

        // ✅ Verificamos que exista la cancha
        if (!Cancha::find($id)) {
            abort(404, 'Cancha no encontrada');
        }

        // Inicializar calendario al mes actual
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

    // ✅ Computed property: obtiene la cancha cuando la necesitamos
    public function getCanchaProperty()
    {
        return Cancha::findOrFail($this->canchaId);
    }

    // ✅ Computed property: obtiene las reservas del mes actual
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

    // Computed property: obtiene el array de días del calendario
    public function getDiasCalendarioProperty()
    {
        $primerDia = \Carbon\Carbon::create($this->añoActual, $this->mesActual, 1);
        $ultimoDia = $primerDia->copy()->endOfMonth();

        // Día de la semana del primer día (0=Domingo, 1=Lunes, etc.)
        $primerDiaSemana = $primerDia->dayOfWeek;

        // Ajustar para que Lunes sea 0
        $primerDiaSemana = $primerDiaSemana == 0 ? 6 : $primerDiaSemana - 1;

        $dias = [];

        // Días vacíos al inicio
        for ($i = 0; $i < $primerDiaSemana; $i++) {
            $dias[] = null;
        }

        // Días del mes
        for ($dia = 1; $dia <= $ultimoDia->day; $dia++) {
            $dias[] = \Carbon\Carbon::create($this->añoActual, $this->mesActual, $dia);
        }

        return $dias;
    }

    // Obtener reservas por fecha
    public function getReservasPorFecha($fecha)
    {
        if (!$fecha) return collect();

        return $this->reservas->filter(function($reserva) use ($fecha) {
            return $reserva->fecha_inicio->isSameDay($fecha);
        });
    }

    // Nombre del mes
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
