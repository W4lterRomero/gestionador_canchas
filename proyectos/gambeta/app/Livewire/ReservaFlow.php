<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Cancha;
use App\Models\Cliente;
use Barryvdh\DomPDF\Facade\Pdf;

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
            $this->precioHora   = $this->canchaObj->precio_hora;
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
        return round(($this->duracion * $this->precioHora), 2);
    }

    public function generarComprobante()
{
    $data = [
        'cancha' => $this->canchaTitulo,
        'fecha'  => $this->fecha,
        'hora'   => $this->hora,
        'duracion' => $this->duracion,
        'total' => $this->total,
        'cliente' => $this->nombre,
        'telefono' => $this->telefono
    ];

    $pdf = Pdf::loadView('pdf.comprobante', $data);

    $fileName = 'comprobante_' . time() . '.pdf';
    $path = storage_path('app/public/comprobantes/' . $fileName);

    $pdf->save($path);

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

    $cliente = \App\Models\Cliente::firstOrCreate(
        ['telefono' => $this->telefono],
        [
            'nombrecliente' => $this->nombre,
            'equipo'        => $this->equipo
        ]
    );

    $inicio = \Carbon\Carbon::parse($this->fecha . ' ' . $this->hora);
    $fin = $inicio->copy()->addHours($this->duracion);

    $duracionMin = $this->duracion * 60;

    \App\Models\Reserva::create([
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

}

