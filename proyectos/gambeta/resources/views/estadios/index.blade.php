@extends('components.layouts.app')

@section('content')

@php
    // ======================================
    // ESTADIOS DE EJEMPLO (Zona Oriental)
    // ======================================
    $estadios = [
        [
            'nombre' => 'Estadio Juan Francisco Barraza',
            'ciudad' => 'San Miguel',
            'tipo' => 'Fútbol rápido',
            'precio' => 45,
            'imagen' => 'images/estadio_barraza.jpg'
        ],
        [
            'nombre' => 'Estadio Félix Charlaix',
            'ciudad' => 'San Miguel',
            'tipo' => 'Fútbol 5',
            'precio' => 35,
            'imagen' => 'images/estadio_charlaix.jpg'
        ],
        [
            'nombre' => 'Estadio Ramón Flores Berríos',
            'ciudad' => 'Santa Rosa de Lima',
            'tipo' => 'Fútbol rápido',
            'precio' => 40,
            'imagen' => 'images/estadio_flores.jpg'
        ],
        [
            'nombre' => 'Estadio Marcelino Imbers',
            'ciudad' => 'La Unión',
            'tipo' => 'Indoor',
            'precio' => 30,
            'imagen' => 'images/estadio_imbers.jpg'
        ],
        [
            'nombre' => 'Estadio Correcaminos',
            'ciudad' => 'San Francisco Gotera',
            'tipo' => 'Fútbol rápido',
            'precio' => 28,
            'imagen' => 'images/estadio_correcaminos.jpg'
        ],
        [
            'nombre' => 'Estadio Municipal Moncagua',
            'ciudad' => 'Moncagua',
            'tipo' => 'Fútbol 5',
            'precio' => 25,
            'imagen' => 'images/estadio_moncagua.jpg'
        ],
    ];

    // ======================================
    // FILTROS
    // ======================================
    $search = request('search');
    $filtroTipo = request('tipo');

    $filtrados = array_filter($estadios, function ($e) use ($search, $filtroTipo) {
        $matchSearch = !$search || stripos($e['nombre'], $search) !== false;
        $matchTipo = !$filtroTipo || $filtroTipo == $e['tipo'];
        return $matchSearch && $matchTipo;
    });
@endphp

<div class="container py-4">

    <!-- HERO -->
    <div class="hero d-flex justify-content-between align-items-center"
        style="background:linear-gradient(90deg,#0b3d91,#1565c0);color:white;
               padding:18px;border-radius:8px;margin-bottom:18px">

        <div>
            <h4 class="mb-0">Estadios</h4>
            <div class="small">Administración y reservaciones del sistema</div>
        </div>

        <a href="{{ route('admin.index') }}" class="btn btn-outline-light">
            Panel de Administración
        </a>

    </div>

    <!-- FORMULARIO DE FILTROS (PHP) -->
    <form method="GET" class="row mb-4 align-items-center">

        <div class="col-md-6">
            <input name="search" value="{{ $search }}" class="form-control"
                   placeholder="Buscar estadio por nombre...">
        </div>

        <div class="col-md-3">
            <select name="tipo" class="form-select">
                <option value="">Filtrar por tipo</option>
                <option {{ $filtroTipo=='Fútbol 5' ? 'selected' : '' }}>Fútbol 5</option>
                <option {{ $filtroTipo=='Fútbol rápido' ? 'selected' : '' }}>Fútbol rápido</option>
                <option {{ $filtroTipo=='Indoor' ? 'selected' : '' }}>Indoor</option>
            </select>
        </div>

        <div class="col-md-3">
            <button class="btn btn-primary w-100">Filtrar</button>
        </div>

    </form>

    <!-- RESULTADOS -->
    <h6 class="text-muted mb-3">{{ count($filtrados) }} estadios encontrados</h6>

    <div class="row g-4">

        @foreach($filtrados as $e)
        <div class="col-md-4">
            <div class="card shadow h-100">

                <img src="{{ asset($e['imagen']) }}" class="card-img-top"
                     style="height:180px;object-fit:cover">

                <div class="card-body">
                    <h5 class="card-title">{{ $e['nombre'] }}</h5>
                    <p class="text-muted small">{{ $e['ciudad'] }}, Zona Oriental</p>

                    <p class="small">
                        Tipo: <b>{{ $e['tipo'] }}</b>
                        <br>
                        Precio por hora: <b>${{ $e['precio'] }}</b>
                    </p>

                    <!-- botón con datos para rellenar el modal -->
                    <button
                        class="btn btn-primary w-100 mt-2 btn-reservar"
                        data-bs-toggle="modal"
                        data-bs-target="#modalReserva"
                        data-nombre="{{ $e['nombre'] }}"
                        data-precio="{{ $e['precio'] }}"
                    >
                        Reservar Estadio
                    </button>
                </div>
            </div>
        </div>
        @endforeach

    </div>

</div>

<!-- ====================================================== -->
<!-- MODAL EMERGENTE PARA RESERVAS DE ESTADIO -->
<!-- ====================================================== -->
<div class="modal fade" id="modalReserva" tabindex="-1">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">

      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title">Gestión de Reservas</h5>
        <button class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>

      <!-- FORM dentro del modal — añade action si vas a enviar -->
      <form id="formReserva" method="POST" action="#">
      <div class="modal-body">

        <!-- ============================================================= -->
        <!-- 🔵 SECCIÓN 1: DATOS DEL CLIENTE -->
        <!-- ============================================================= -->
        <h5 class="mb-3">Registrar cliente</h5>

        <div class="row g-3 mb-4">

            <div class="col-md-4">
                <label class="form-label">Nombre completo</label>
                <input name="cliente_nombre" type="text" class="form-control" placeholder="Nombre del cliente" required>
            </div>

            <div class="col-md-4">
                <label class="form-label">Teléfono</label>
                <input name="cliente_telefono" type="text" class="form-control" placeholder="0000-0000" required>
            </div>

            <div class="col-md-4">
                <label class="form-label">Equipo o grupo</label>
                <input name="cliente_equipo" type="text" class="form-control" placeholder="Nombre del equipo">
            </div>

        </div>

        <!-- ============================================================= -->
        <!-- 🔵 SECCIÓN 2: DATOS DE LA RESERVA -->
        <!-- ============================================================= -->
        <h5 class="mb-3">Reservar Estadio</h5>

        <div class="row g-3 mb-4">

            <div class="col-md-6">
                <label class="form-label">Estadio</label>
                <select name="estadio" id="res_estadio" class="form-select" required>
                    @foreach($estadios as $s)
                        <option value="{{ $s['nombre'] }}">{{ $s['nombre'] }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-3">
                <label class="form-label">Fecha</label>
                <input name="fecha" type="date" id="res_fecha" class="form-control" required>
            </div>

            <div class="col-md-3">
                <label class="form-label">Hora</label>
                <input name="hora" type="time" id="res_hora" class="form-control" required>
            </div>

            <div class="col-md-4">
                <label class="form-label">Duración (horas)</label>
                <input name="duracion" id="res_duracion" type="number" min="1" class="form-control" value="1" required>
            </div>

            <div class="col-md-4">
                <label class="form-label">Precio por hora (USD)</label>
                <input name="precio_hora" id="res_precio" type="number" class="form-control" step="0.01" required>
            </div>

            <div class="col-md-4">
                <label class="form-label">Precio total (USD)</label>
                <input name="precio_total" id="res_total" type="text" class="form-control" placeholder="Se calcula automáticamente" readonly>
            </div>

        </div>

        <!-- ============================================================= -->
        <!-- 🔵 SECCIÓN 3: ESTADO DE LA RESERVA -->
        <!-- ============================================================= -->
        <h5 class="mb-3">Estado de la reserva</h5>

        <div class="row g-3">

            <div class="col-md-4">
                <label class="form-label">Estado</label>
                <select name="estado" id="res_estado" class="form-select">
                    <option value="Pendiente">Pendiente</option>
                    <option value="Confirmada">Confirmada</option>
                    <option value="Cancelada">Cancelada</option>
                    <option value="Finalizada">Finalizada</option>
                </select>
            </div>

        </div>

      </div>

      <!-- FOOTER -->
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
        <button type="submit" class="btn btn-primary">Guardar Reserva</button>
      </div>
      </form>

    </div>
  </div>
</div>

<!-- Minimal JS to wire modal and calculate total -->
<script>
document.addEventListener('DOMContentLoaded', function () {
    // Cuando se hace click en cualquier botón "Reservar"
    document.querySelectorAll('.btn-reservar').forEach(btn => {
        btn.addEventListener('click', function () {
            const nombre = this.dataset.nombre || '';
            const precio = parseFloat(this.dataset.precio || 0);

            // rellenar select y precio
            const select = document.getElementById('res_estadio');
            select.value = nombre; // si existe, selecciona la opcion
            document.getElementById('res_precio').value = precio.toFixed(2);

            // calcular total inicialmente
            updateTotal();
        });
    });

    // actualizar total cuando cambian precio o duracion
    const duracion = document.getElementById('res_duracion');
    const precioInput = document.getElementById('res_precio');

    function updateTotal() {
        const h = parseFloat(duracion.value) || 0;
        const p = parseFloat(precioInput.value) || 0;
        const total = h * p;
        document.getElementById('res_total').value = total.toFixed(2);
    }

    duracion.addEventListener('input', updateTotal);
    precioInput.addEventListener('input', updateTotal);

    // Validación mínima (opcional) antes de submit — puedes reemplazar por envío real
    document.getElementById('formReserva').addEventListener('submit', function(e){
        // ejemplo: evitar envío real porque action="#" por ahora
        e.preventDefault();
        // recolectar datos y mostrar un resumen (puedes reemplazar con fetch / POST cuando quieras)
        const data = Object.fromEntries(new FormData(this));
        alert('Reserva lista para guardar:\\nEstadio: ' + data.estadio + '\\nFecha: ' + data.fecha + '\\nTotal: $' + data.precio_total);
        // cerrar modal automáticamente
        const modalEl = document.getElementById('modalReserva');
        const modal = bootstrap.Modal.getInstance(modalEl);
        if(modal) modal.hide();
    });
});
</script>

@endsection
