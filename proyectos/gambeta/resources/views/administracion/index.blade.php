@extends('components.layouts.app')

@php
    $canchas = $canchas ?? collect();
    $editarCanchaId = session('editarCanchaId');
    $shouldOpenCreateModal = $errors->crearCancha->any();
    $feedbackStatus = session('status');
    $feedbackError = session('error');
    $feedbackMessage = $feedbackStatus ?: $feedbackError;
    $feedbackType = $feedbackStatus ? 'success' : ($feedbackError ? 'error' : null);
    $isErrorFeedback = $feedbackType === 'error';
    $feedbackTitle = $isErrorFeedback ? 'Ocurrió un problema' : 'Operación exitosa';
@endphp

@section('content')

<div class="container py-4">

    <!-- Header -->
    <div class="hero mb-4"
        style="background:linear-gradient(90deg,#0b3d91,#1565c0);color:white;padding:18px;border-radius:8px;">
        <h3 class="mb-0">Panel de Administración</h3>
        <div class="small">Gestión general del sistema</div>
    </div>


    <!-- ============================================================= -->
    <!-- 🔵 SECCIÓN 1: VER TODAS LAS RESERVAS -->
    <!-- ============================================================= -->
    <section class="mb-5">

        <div class="card shadow">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">Ver todas las reservas</h5>
            </div>

            <div class="card-body">

                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Filtrar por fecha</label>
                        <input type="date" class="form-control">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Filtrar por cancha</label>
                        <select class="form-select">
                            <option>Todas</option>
                            <option>Cancha Central</option>
                            <option>Indoor Arena</option>
                            <option>Rápida Norte</option>
                        </select>
                    </div>
                </div>

                <!-- Tabla de ejemplo (solo diseño) -->
                <table class="table table-striped">
                    <thead class="table-primary">
                        <tr>
                            <th>Fecha</th>
                            <th>Cancha</th>
                            <th>Cliente</th>
                            <th>Horario</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>2025-01-10</td>
                            <td>Cancha Central</td>
                            <td>Juan Pérez</td>
                            <td>10:00 - 11:00</td>
                        </tr>
                        <tr>
                            <td>2025-01-11</td>
                            <td>Indoor Arena</td>
                            <td>María López</td>
                            <td>14:00 - 15:00</td>
                        </tr>
                    </tbody>
                </table>

            </div>

        </div>

        <div class="flex justify-end mt-6">
            <a href="{{ route('reservas.index') }}"
               class="mary-btn bg-green-600 hover:bg-green-500 text-white rounded-full px-6">
                Ver todas las reservas
            </a>
        </div>

    </section>



    <!-- ============================================================= -->
    <!-- 🔵 SECCIÓN 2: BLOQUEAR HORARIOS -->
    <!-- ============================================================= -->
    <section class="mb-5">

        <div class="card shadow">
            <div class="card-header bg-warning text-dark">
                <h5 class="mb-0">Bloquear horarios especiales</h5>
            </div>

            <div class="card-body">

                <p class="text-muted">
                    Configure bloqueos temporales para mantenimiento, eventos o actividades exclusivas.
                </p>

                <div class="row g-3">

                    <div class="col-md-4">
                        <label class="form-label">Cancha</label>
                        <select class="form-select">
                            <option>Cancha Central</option>
                            <option>Indoor Arena</option>
                            <option>Rápida Norte</option>
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Fecha del bloqueo</label>
                        <input type="date" class="form-control">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Motivo</label>
                        <input type="text" class="form-control" placeholder="Mantenimiento, evento, etc.">
                    </div>

                </div>

                <div class="mt-3 text-end">
                    <button class="btn btn-warning">Guardar bloqueo</button>
                </div>

                <hr class="my-4">

                <h6>Bloqueos recientes (ejemplo)</h6>
                <ul class="list-group">
                    <li class="list-group-item">
                        <b>Cancha Central</b> — 2025-01-15 <br>
                        Motivo: Mantenimiento general
                    </li>
                </ul>

            </div>
        </div>

    </section>



    <!-- ============================================================= -->
    <!-- 🔵 SECCIÓN 3: GESTIONAR PRECIOS -->
    <!-- ============================================================= -->
    <section class="mb-5">

        <div class="card shadow">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0">Gestionar precios</h5>
            </div>

            <div class="card-body">

                <p class="text-muted">
                    Ajusta el precio por hora de cada cancha.
                </p>

                <div class="row g-3">

                    <div class="col-md-6">
                        <label class="form-label">Cancha</label>
                        <select class="form-select">
                            <option value="f1">Cancha Central</option>
                            <option value="f2">Indoor Arena</option>
                            <option value="f3">Rápida Norte</option>
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Nuevo precio (USD)</label>
                        <input type="number" class="form-control">
                    </div>

                </div>

                <div class="mt-3 text-end">
                    <button class="btn btn-success">Guardar precio</button>
                </div>

                <hr class="my-4">

                <h6>Precios actuales (ejemplo)</h6>

                <ul class="list-group mt-2">
                    <li class="list-group-item">
                        <b>Cancha Central</b>: $20/h
                    </li>
                    <li class="list-group-item">
                        <b>Indoor Arena</b>: $35/h
                    </li>
                    <li class="list-group-item">
                        <b>Rápida Norte</b>: $18/h
                    </li>
                </ul>

            </div>

        </div>

    </section>

</div>

</section>

@endsection
