<?php

namespace App\Livewire;

use App\Models\Cliente;
use Livewire\Component;
use Livewire\WithPagination;

class ClientesManager extends Component
{
    use WithPagination;

    /**
     * Búsqueda de clientes.
     */
    public string $search = '';

    /**
     * Filtro por tipo de cliente.
     */
    public string $filtro = 'todos'; // todos, frecuentes, normales

    /**
     * Ordenamiento.
     */
    public string $ordenarPor = 'nombre'; // nombre, total_reservas, ultima_reserva

    /**
     * Modal de edición/creación.
     */
    public bool $modalAbierto = false;
    public bool $esEdicion = false;
    public ?int $clienteId = null;

    /**
     * Datos del formulario.
     */
    public string $nombre = '';
    public string $telefono = '';
    public string $equipo = '';
    public string $email = '';
    public string $notas = '';
    public bool $es_frecuente = false;
    public float $descuento_porcentaje = 0;


    public bool $modalEliminar = false;
    public ?int $clienteEliminarId = null;

    protected $listeners = ['clienteActualizado' => '$refresh'];

    protected function rules()
    {
        return [
            'nombre' => 'required|string|max:150',
            'telefono' => 'required|string|max:50',
            'equipo' => 'nullable|string|max:100',
            'email' => 'nullable|email|max:150',
            'notas' => 'nullable|string',
            'es_frecuente' => 'boolean',
            'descuento_porcentaje' => 'numeric|min:0|max:100',
        ];
    }


    public function updatingSearch()
    {
        $this->resetPage();
    }


    public function updatingFiltro()
    {
        $this->resetPage();
    }


    public function crearCliente()
    {
        $this->resetForm();
        $this->esEdicion = false;
        $this->modalAbierto = true;
    }


    public function editarCliente($id)
    {
        $cliente = Cliente::findOrFail($id);

        $this->clienteId = $cliente->id;
        $this->nombre = $cliente->nombre;
        $this->telefono = $cliente->telefono;
        $this->equipo = $cliente->equipo ?? '';
        $this->email = $cliente->email ?? '';
        $this->notas = $cliente->notas ?? '';
        $this->es_frecuente = $cliente->es_frecuente;
        $this->descuento_porcentaje = (float) $cliente->descuento_porcentaje;

        $this->esEdicion = true;
        $this->modalAbierto = true;
    }


    public function guardarCliente()
    {
        $this->validate();

        try {
            $data = [
                'nombre' => $this->nombre,
                'telefono' => $this->telefono,
                'equipo' => $this->equipo,
                'email' => $this->email,
                'notas' => $this->notas,
                'es_frecuente' => $this->es_frecuente,
                'descuento_porcentaje' => $this->descuento_porcentaje,
            ];

            if ($this->esEdicion) {
                $cliente = Cliente::findOrFail($this->clienteId);
                $cliente->update($data);
                session()->flash('message', 'Cliente actualizado exitosamente');
            } else {
                $data['fecha_registro'] = now();
                Cliente::create($data);
                session()->flash('message', 'Cliente creado exitosamente');
            }

            $this->modalAbierto = false;
            $this->resetForm();
            $this->dispatch('clienteActualizado');
        } catch (\Exception $e) {
            session()->flash('error', 'Error al guardar el cliente: ' . $e->getMessage());
        }
    }


    public function confirmarEliminar($id)
    {
        $this->clienteEliminarId = $id;
        $this->modalEliminar = true;
    }

    public function eliminarCliente()
    {
        try {
            $cliente = Cliente::findOrFail($this->clienteEliminarId);

            if ($cliente->reservas()->count() > 0) {
                session()->flash('error', 'No se puede eliminar el cliente porque tiene reservas asociadas');
            } else {
                $cliente->delete();
                session()->flash('message', 'Cliente eliminado exitosamente');
            }

            $this->modalEliminar = false;
            $this->clienteEliminarId = null;
        } catch (\Exception $e) {
            session()->flash('error', 'Error al eliminar el cliente: ' . $e->getMessage());
        }
    }

    public function toggleFrecuente($id)
    {
        try {
            $cliente = Cliente::findOrFail($id);

            if ($cliente->es_frecuente) {
                $cliente->desmarcarComoFrecuente();
                session()->flash('message', 'Cliente desmarcado como frecuente');
            } else {
                $cliente->marcarComoFrecuente(0);
                session()->flash('message', 'Cliente marcado como frecuente');
            }
        } catch (\Exception $e) {
            session()->flash('error', 'Error al actualizar el cliente: ' . $e->getMessage());
        }
    }


    public function actualizarEstadisticas($id)
    {
        try {
            $cliente = Cliente::findOrFail($id);
            $cliente->actualizarEstadisticas();
            session()->flash('message', 'Estadísticas actualizadas');
        } catch (\Exception $e) {
            session()->flash('error', 'Error al actualizar estadísticas: ' . $e->getMessage());
        }
    }


    private function resetForm()
    {
        $this->clienteId = null;
        $this->nombre = '';
        $this->telefono = '';
        $this->equipo = '';
        $this->email = '';
        $this->notas = '';
        $this->es_frecuente = false;
        $this->descuento_porcentaje = 0;
    }


    public function cerrarModal()
    {
        $this->modalAbierto = false;
        $this->resetForm();
    }

   
    public function render()
    {
        $query = Cliente::query()->withCount('reservas');

        // Aplicar búsqueda
        if (!empty($this->search)) {
            $query->where(function ($q) {
                $q->where('nombre', 'like', '%' . $this->search . '%')
                  ->orWhere('telefono', 'like', '%' . $this->search . '%')
                  ->orWhere('email', 'like', '%' . $this->search . '%')
                  ->orWhere('equipo', 'like', '%' . $this->search . '%');
            });
        }

        // Aplicar filtro
        if ($this->filtro === 'frecuentes') {
            $query->where('es_frecuente', true);
        } elseif ($this->filtro === 'normales') {
            $query->where('es_frecuente', false);
        }

        // Aplicar ordenamiento
        switch ($this->ordenarPor) {
            case 'total_reservas':
                $query->orderBy('reservas_count', 'desc');
                break;
            case 'ultima_reserva':
                $query->orderBy('ultima_reserva', 'desc');
                break;
            default:
                $query->orderBy('nombre', 'asc');
        }

        $clientes = $query->paginate(15);

        return view('livewire.clientes-manager', [
            'clientes' => $clientes,
        ]);
    }
}
