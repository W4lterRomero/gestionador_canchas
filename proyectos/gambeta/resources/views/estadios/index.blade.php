@extends('components.layouts.app')

@section('content')


<!-- Livewire: Listado y Filtros -->
<livewire:estadios />


<!-- Modal de Reserva (Bootstrap) -->
<div class="modal fade" id="modalReserva" tabindex="-1">
  <div class="modal-dialog modal-xl">
    <div class="modal-content rounded-2xl overflow-hidden">

      <div class="modal-header bg-green-700 text-white">
        <h5 class="modal-title">Gestión de Reservas</h5>
        <button class="btn-close bg-white rounded-full p-2" data-bs-dismiss="modal"></button>
      </div>

      <form id="formReserva" method="POST" action="#">
      <div class="modal-body">

        <!-- Cliente -->
        <h4 class="text-green-700 mb-3 font-bold">Datos del Cliente</h4>

        <div class="row g-3 mb-4">

            <div class="col-md-4">
                <label class="form-label">Nombre completo</label>
                <input name="cliente_nombre" type="text" class="form-control" required>
            </div>

            <div class="col-md-4">
                <label class="form-label">Teléfono</label>
                <input name="cliente_telefono" type="text" class="form-control" required>
            </div>

            <div class="col-md-4">
                <label class="form-label">Equipo o grupo</label>
                <input name="cliente_equipo" type="text" class="form-control">
            </div>

        </div>


        <!-- Reserva -->
        <h4 class="text-green-700 mb-3 font-bold">Datos de la Reserva</h4>

        <div class="row g-3 mb-4">

            <div class="col-md-6">
                <label class="form-label">Estadio</label>
                <select name="estadio" id="res_estadio" class="form-select" required>
                    <!-- Estadio será llenado dinámicamente por el botón -->
                </select>
            </div>

            <div class="col-md-3">
                <label class="form-label">Fecha</label>
                <input name="fecha" type="date" class="form-control" required>
            </div>

            <div class="col-md-3">
                <label class="form-label">Hora</label>
                <input name="hora" type="time" class="form-control" required>
            </div>

            <div class="col-md-4 mt-3">
                <label class="form-label">Duración (horas)</label>
                <input name="duracion" id="res_duracion" type="number" value="1" min="1" class="form-control">
            </div>

            <div class="col-md-4 mt-3">
                <label class="form-label">Precio por hora (USD)</label>
                <input name="precio_hora" id="res_precio" type="number" step="0.01" class="form-control">
            </div>

            <div class="col-md-4 mt-3">
                <label class="form-label">Precio total</label>
                <input name="precio_total" id="res_total" type="text" class="form-control" readonly>
            </div>

        </div>


        <!-- Estado -->
        <h4 class="text-green-700 mb-3 font-bold">Estado de la Reserva</h4>

        <select name="estado" class="form-select">
            <option>Pendiente</option>
            <option>Confirmada</option>
            <option>Cancelada</option>
            <option>Finalizada</option>
        </select>

      </div>

      <div class="modal-footer">
        <button type="button" class="mary-btn bg-gray-300 hover:bg-gray-400 text-gray-800 rounded-full px-5"
                data-bs-dismiss="modal">Cerrar</button>

        <button type="submit" class="mary-btn bg-green-600 hover:bg-green-500 text-white rounded-full px-5">
            Guardar Reserva
        </button>
      </div>

      </form>

    </div>
  </div>
</div>

@endsection
