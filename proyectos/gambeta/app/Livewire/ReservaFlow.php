<?php

namespace App\Livewire;

use App\Models\BloqueoHorario;
use App\Models\Cancha;
use App\Models\Cliente;
use App\Models\Reserva;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Validation\ValidationException;
use Livewire\Component;

class ReservaFlow extends Component
{
    public $pasoActual = 1;

    public $canchas = [];
    public $cancha;
    public $canchaObj = null;
    public $canchaTitulo = null;
    public $canchaImagen = null;

    public $fecha;
    public $hora;
    public $duracion = 1;
    public $precioHora = 0;

    public $nombre;
    public $telefono;
    public $equipo;
    public $email;
    public $notas;

    public $busqueda = '';
    public $resultados = [];
    public $clienteExiste = false;

    public $estadoReserva;
    public $estadoPago;
    public $totalPagar;
    public $adelanto;
    public $metodoPago;
    public $observaciones;

    public $mostrarModalConfirmacion = false;
    public $mostrarModalExito = false;

    public $comprobantePdf = null;

    protected $listeners = [
        'fechaSeleccionada' => 'setFecha',
        'horaSeleccionada' => 'setHora',
        'reset-calendario' => '$refresh'
    ];

    protected $messages = [
    'cancha.required'      => 'Debes seleccionar una cancha.',
    'fecha.required'       => 'Debes seleccionar una fecha.',
    'hora.required'        => 'Debes seleccionar una hora.',
    'duracion.required'    => 'La duración es obligatoria.',
    'nombre.required'      => 'El nombre del cliente es obligatorio.',
    'telefono.required'    => 'El teléfono es obligatorio.',
    'estadoPago.required'  => 'Debes seleccionar un estado de pago.',
    'metodoPago.required'  => 'Debes seleccionar un método de pago.',
    'adelanto.numeric'     => 'El adelanto debe ser un número válido.',
    'precioHora.required'  => 'El precio por hora no está definido para esta cancha.',
    ];

    public function mount()
    {
        $this->canchas = Cancha::where('activa', 1)->get();
    }

    public function updatedCancha($id)
    {
        $this->canchaObj = Cancha::find($id);

        if ($this->canchaObj) {
            $this->canchaTitulo = $this->canchaObj->nombre;
            $this->canchaImagen = asset($this->canchaObj->imagen_url);
            $this->precioHora   = $this->canchaObj->precioHoraVigente();
            $this->hora = null;

            $this->dispatch('reset-calendario');
        }
    }

    public function setFecha($fecha)
    {
        $this->fecha = $fecha;
    }

    public function setHora($hora)
    {
        $this->hora = $hora;
    }

    public function siguiente()
    {
        $this->validarPasoActual();

        if ($this->pasoActual < 4) {
            $this->pasoActual++;
        }
    }

    public function anterior()
    {
        if ($this->pasoActual > 1) {
            $this->pasoActual--;
        }
    }

    public function irPaso($num)
    {
        if ($num < $this->pasoActual) {
            $this->pasoActual = $num;
            return;
        }

        $this->validarPasoActual();
        $this->pasoActual = $num;
    }

    public function validarPasoActual()
    {
        if ($this->pasoActual == 1) {
            $this->validate([
                'cancha'     => 'required',
                'fecha'      => 'required|date',
                'hora'       => 'required|string',
                'duracion'   => 'required|numeric|min:1',
                'precioHora' => 'required|numeric|min:0'
            ]);

            $this->asegurarHorarioDisponible();
        }

        if ($this->pasoActual == 2) {
            $this->validate([
                'nombre' => 'required|string|min:3',
                'telefono' => 'required|min:9|max:9',
            ], [
                'telefono.required' => 'El teléfono es obligatorio.',
                'telefono.min' => 'El formato debe ser 0000-0000.',
                'telefono.max' => 'El formato debe ser 0000-0000.',
            ]);
            $this->guardarCliente();
        }

        if ($this->pasoActual == 3) {
            $this->validate([
                'estadoPago' => 'required|string',
                'metodoPago' => 'required|string',
                'adelanto' => 'nullable|numeric|min:0'
            ]);
        }
    }

    public function updatedBusqueda()
    {
        if (trim($this->busqueda) == '') {
            $this->resetCliente();
            return;
        }

        if (strlen($this->busqueda) < 2) {
            $this->resultados = [];
            return;
        }

        $this->resultados = Cliente::where('nombre', 'LIKE', "%{$this->busqueda}%")
            ->limit(5)
            ->get();
    }

    public function resetCliente()
    {
        $this->clienteExiste = false;
        $this->busqueda = '';
        $this->nombre = '';
        $this->telefono = '';
        $this->equipo = '';
        $this->email = '';
        $this->notas = '';
        $this->resultados = [];
    }

    public function seleccionarCliente($id)
    {
        $c = Cliente::find($id);

        $this->nombre   = $c->nombre;
        $this->telefono = $c->telefono;
        $this->equipo   = $c->equipo;
        $this->email    = $c->email;
        $this->notas    = $c->notas;

        $this->clienteExiste = true;
        $this->busqueda = $c->nombre;
        $this->resultados = [];
    }

    public function guardarCliente()
    {
        if ($this->clienteExiste) return;

        $cliente = Cliente::firstOrCreate(
            ['telefono' => $this->telefono],
            [
                'nombre' => $this->nombre,
                'equipo' => $this->equipo,
                'email' => $this->email,
                'notas' => $this->notas,
            ]
        );

        $this->clienteExiste = true;
    }

    public function updatedTelefono($value)
    {
        $num = preg_replace('/\D/', '', $value);
        $num = substr($num, 0, 8);
        if (strlen($num) > 4) {
            $this->telefono = substr($num, 0, 4) . '-' . substr($num, 4);
        } else {
            $this->telefono = $num;
        }
    }

    public function getTotalProperty()
    {
        $duracion = $this->obtenerDuracionHoras();
        if ($duracion === null) {
            return 0.00;
        }

        $precio = is_numeric($this->precioHora) ? (float) $this->precioHora : 0.00;

        return round(($duracion * $precio), 2);
    }

public function generarComprobante()
{
    $data = [
        'cancha'      => $this->canchaTitulo,
        'fecha'       => $this->fecha,
        'hora'        => $this->hora,
        'duracion'    => $this->duracion,
        'total'       => $this->total,
        'cliente'     => $this->nombre,
        'telefono'    => $this->telefono,
        'estadoPago'  => $this->estadoPago,
        'totalPagar'  => $this->totalPagar,
        'adelanto'    => $this->adelanto ?? 0,
        'metodoPago'  => $this->metodoPago,
    ];

    // Asegurar que la carpeta exista
    $folder = storage_path('app/public/comprobantes');
    if (!file_exists($folder)) {
        mkdir($folder, 0775, true);
    }

    $fileName = 'comprobante_' . time() . '.pdf';
    $path = $folder . '/' . $fileName;

    $pdf = Pdf::loadView('pdf.comprobante', $data);
    $pdf->save($path);

    // Ruta pública
    $this->comprobantePdf = 'storage/comprobantes/' . $fileName;
}



    public function finalizar()
    {
        $this->validarPasoActual();

        session()->flash('mensaje', 'Reserva creada con éxito');

        $this->reset([
            'cancha',
            'canchaObj',
            'canchaTitulo',
            'canchaImagen',
            'fecha',
            'hora',
            'duracion',
            'precioHora',
            'nombre',
            'telefono',
            'equipo',
            'estadoPago',
            'adelanto',
            'metodoPago',
        ]);

        $this->pasoActual = 1;
    }

    public function render()
    {
        return view('livewire.reserva-flow');
    }

    public function abrirConfirmacion()
{
    $this->validarPasoActual();  
    $this->mostrarModalConfirmacion = true;
}

public function cancelarConfirmacion()
{
    $this->mostrarModalConfirmacion = false;
}

public function confirmarGuardado()
{
    $this->mostrarModalConfirmacion = false;

    $this->guardarReserva();

    $this->mostrarModalExito = true;
}

private function guardarReserva()
{
    $this->validarPasoActual();
    $this->asegurarHorarioDisponible();

    $duracionHoras = $this->obtenerDuracionHoras();
    if ($duracionHoras === null) {
        throw ValidationException::withMessages([
            'duracion' => 'Debes ingresar una duración válida.',
        ]);
    }

    $cliente = Cliente::firstOrCreate(
        ['telefono' => $this->telefono],
        [
            'nombrecliente' => $this->nombre,
            'equipo'        => $this->equipo
        ]
    );

    $inicio = Carbon::parse($this->fecha . ' ' . $this->hora, 'America/El_Salvador');
    $fin = $inicio->copy()->addHours($duracionHoras);

    $duracionMin = (int) round($duracionHoras * 60);

    Reserva::create([
        'cancha_id'        => $this->cancha,
        'cliente_id'       => $cliente->id,
        'fecha_reserva'    => $this->fecha, 
        'fecha_inicio'     => $inicio,
        'fecha_fin'        => $fin,
        'duracion_minutos' => $duracionMin,
        'precio_hora'      => $this->precioHora,
        'total'            => $this->total,
        'estado'           => $this->estadoReserva ?: 'Pendiente',
        'observaciones'    => null,
        'creado_por'       => auth()->id() ?? 1,
        'actualizado_por'  => auth()->id() ?? 1,
    ]);

    $this->resetExcept('canchas');
    $this->pasoActual = 1;
}

private function asegurarHorarioDisponible(): void
{
    if (! $this->cancha || ! $this->fecha || ! $this->hora) {
        return;
    }

    $duracionHoras = $this->obtenerDuracionHoras();

    if ($duracionHoras === null || $duracionHoras <= 0) {
        return;
    }

    $inicio = Carbon::parse($this->fecha . ' ' . $this->hora, 'America/El_Salvador');
    $fin = $inicio->copy()->addHours($duracionHoras);

    $conflictoBloqueo = BloqueoHorario::query()
        ->where('cancha_id', $this->cancha)
        ->where('fecha_inicio', '<', $fin)
        ->where('fecha_fin', '>', $inicio)
        ->exists();

    if ($conflictoBloqueo) {
        throw ValidationException::withMessages([
            'hora' => 'La cancha está bloqueada por el administrador en el horario seleccionado.',
        ]);
    }
}

public function actualizarEstadoPago()
{
    $totalReserva = number_format($this->total, 2, '.', '');

    if ($this->estadoPago === 'pagado') {
        $this->totalPagar = $totalReserva;
    }

    if ($this->estadoPago === 'adelanto') {
        if (!$this->totalPagar) {
            $this->totalPagar = $totalReserva;
        }
    }

    if ($this->estadoPago === 'nopago') {
        $this->totalPagar = '0.00';
        $this->adelanto = 0;
    }
}



public function validarTotalPagar()
{
    $this->totalPagar = preg_replace('/[^0-9.]/', '', $this->totalPagar);

    if (is_numeric($this->totalPagar)) {
        $this->totalPagar = number_format((float)$this->totalPagar, 2, '.', '');
    }
}

public function validarAdelanto()
{
    $this->adelanto = preg_replace('/[^0-9.]/', '', $this->adelanto);

    if (is_numeric($this->adelanto)) {
        $this->adelanto = number_format((float)$this->adelanto, 2, '.', '');
    }
}

    private function obtenerDuracionHoras(): ?float
    {
        if ($this->duracion === null || $this->duracion === '') {
            return null;
        }

        return is_numeric($this->duracion) ? (float) $this->duracion : null;
    }

}
