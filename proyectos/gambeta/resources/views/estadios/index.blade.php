@extends('components.layouts.app')

@section('content')

<div class="container py-4">

    <!-- HERO (estructura solamente, sin filtros de rol aún) -->
    <div class="hero d-flex justify-content-between align-items-center"
        style="background:linear-gradient(90deg,#0b3d91,#1565c0);color:white;padding:18px;border-radius:8px;margin-bottom:18px">

        <div>
            <h4 class="mb-0">Canchas</h4>
            <div class="small">Administración y reservaciones del sistema</div>
        </div>

        <div class="d-flex gap-2">

            <!-- Gestión de canchas -->
            <div class="dropdown">
                <button class="btn btn-light dropdown-toggle" type="button" data-bs-toggle="dropdown">
                    Gestión de Canchas
                </button>

                <ul class="dropdown-menu">
                    <li>
                        <a class="dropdown-item" href="#" id="btnOpenNew">
                            Registrar nueva cancha
                        </a>
                    </li>

                    <li>
                        <span class="dropdown-item-text small text-muted">
                            Registrar canchas con nombre, tipo, precio por hora y disponibilidad.
                        </span>
                    </li>

                    <li><hr class="dropdown-divider"></li>

                    <li>
                        <span class="dropdown-item-text small text-muted">
                            Subir fotografía o imagen representativa.
                        </span>
                    </li>
                </ul>
            </div>

            <!-- Panel de administración -->
            <a href="{{ route('admin.index') }}" class="btn btn-outline-light">
                Panel de Administración
            </a>

        </div>
    </div>

    <!-- Filtros (Estructura solamente) -->
    <div class="row mb-3 align-items-center">

        <div class="col-md-6">
            <input id="searchField" class="form-control" placeholder="Buscar por nombre o tipo...">
        </div>

        <div class="col-md-3">
            <select id="filterType" class="form-select">
                <option value="">Filtrar por tipo</option>
                <option>Fútbol 5</option>
                <option>Fútbol rápido</option>
                <option>Indoor</option>
            </select>
        </div>

        <div class="col-md-3 text-end">
            <span class="small-muted" id="countFields">0 canchas</span>
        </div>

    </div>

    <!-- Contenedor de tarjetas -->
    <div id="cardsRow" class="row g-3"></div>

</div>


<!-- MODAL Registrar Cancha (estructura intacta) -->
<div class="modal fade" id="modalNew" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">

      <form id="formField">

        <div class="modal-header">
            <h5 class="modal-title">Registrar Cancha</h5>
            <button class="btn-close" data-bs-dismiss="modal"></button>
        </div>

        <div class="modal-body">

          <label class="form-label">Nombre</label>
          <input name="name" class="form-control">

          <label class="form-label mt-2">Tipo</label>
          <select name="type" class="form-select">
            <option>Fútbol 5</option>
            <option>Fútbol rápido</option>
            <option>Indoor</option>
          </select>

          <label class="form-label mt-2">Precio por hora (USD)</label>
          <input name="price" type="number" min="0" class="form-control">

          <label class="form-label mt-2">Horario disponible</label>
          <input name="hours" class="form-control" placeholder="08:00-23:00">

          <label class="form-label mt-2">Imagen (opcional)</label>
          <input id="fileImg" type="file" accept="image/*" class="form-control">

          <img id="imgPreview" class="img-preview mt-2 d-none" 
               style="height:120px;object-fit:cover;border-radius:8px">
        
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
          <button class="btn btn-primary" type="submit">Guardar cancha</button>
        </div>

      </form>

    </div>
  </div>
</div>

@endsection
