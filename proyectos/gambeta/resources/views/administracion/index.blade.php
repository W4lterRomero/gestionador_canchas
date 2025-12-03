@extends('components.layouts.app')

@php
    $canchas = $canchas ?? collect();
    $reservas = $reservas ?? collect();
    $bloqueos = $bloqueos ?? collect();
    $precios = $precios ?? collect();
    $usuarios = $usuarios ?? collect();
    $clientes = $clientes ?? collect();
    $roles = $roles ?? collect();
    $editarCanchaId = session('editarCanchaId');
    $editarBloqueoId = session('editarBloqueoId');
    $editarPrecioId = session('editarPrecioId');
    $editarUsuarioId = session('editarUsuarioId');
    $editarReservaId = session('editarReservaId');
    $shouldOpenCreateModal = $errors->crearCancha->any();
    $shouldOpenBloqueoModal = $errors->crearBloqueo->any();
    $shouldOpenPrecioModal = $errors->crearPrecio->any();
    $shouldOpenUsuarioModal = $errors->crearUsuario->any();
    $crearBloqueoErrors = $errors->crearBloqueo ?? null;
    $editarBloqueoErrors = $errors->editarBloqueo ?? null;
    $crearPrecioErrors = $errors->crearPrecio ?? null;
    $editarPrecioErrors = $errors->editarPrecio ?? null;
    $usuarioEditErrors = $errors->editarUsuario ?? null;
    $reservaEditErrors = $errors->editarReserva ?? null;
    $feedbackStatus = session('status');
    $feedbackError = session('error');
    $feedbackMessage = $feedbackStatus ?: $feedbackError;
    $feedbackType = $feedbackStatus ? 'success' : ($feedbackError ? 'error' : null);
    $isErrorFeedback = $feedbackType === 'error';
    $feedbackTitle = $isErrorFeedback ? 'Ocurrió un problema' : 'Operación exitosa';
    $reservasJsVersion = file_exists(public_path('js/reservas.js')) ? filemtime(public_path('js/reservas.js')) : time();
    $adminCalendarJsVersion = file_exists(public_path('js/admin-calendar.js')) ? filemtime(public_path('js/admin-calendar.js')) : time();
    $canchaTipos = $canchas->pluck('tipo')->filter()->unique()->sort()->values();
    $reservaTipos = $reservas->map(function ($reserva) {
        return optional($reserva->cancha)->tipo;
    })->filter()->unique()->sort()->values();
    $bloqueoTipos = $bloqueos->map(function ($bloqueo) {
        return optional($bloqueo->cancha)->tipo;
    })->filter()->unique()->sort()->values();
    $precioTipos = $precios->map(function ($precio) {
        return optional($precio->cancha)->tipo;
    })->filter()->unique()->sort()->values();
    $usuarioRoles = $usuarios->map(function ($usuario) {
        return optional($usuario->role)->description ?? optional($usuario->role)->name;
    })->filter()->unique()->sort()->values();
@endphp

@section('content')
<header class="bg-gradient-to-r from-emerald-950 via-emerald-900 to-emerald-600 text-white p-8 shadow-xl">
    <div class="flex flex-col gap-2">
        <p class="text-sm uppercase tracking-[0.3em] text-green-100">PANEL DE ADMINISTRADOR</p>
    </div>
</header>
{{-- NAVBAR --}}
<nav id="admin-panels-nav"
    class="sticky top-0 z-50 bg-white shadow-lg border-b border-gray-200 px-3 sm:px-5 py-3">
    <ul class="flex w-full flex-wrap items-center gap-1 sm:gap-0 sm:-mx-1 text-sm font-medium">

        {{-- CANCHAS --}}
        <li class="block">
            <button type="button"
                data-section-target="canchas"
                data-default="true"
                class="panel-tab flex items-center h-9 sm:h-10 leading-10 px-2 sm:px-4 rounded-md sm:mx-1 cursor-pointer
                           transition-colors duration-100
                           bg-emerald-200 text-emerald-900 shadow hover:bg-emerald-300">
                <span class="text-xl">
                    {{-- Icono cancha --}}
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-900" viewBox="0 0 24 24"
                        fill="none" stroke="currentColor" stroke-width="1.7"
                        stroke-linecap="round" stroke-linejoin="round">
                        <rect x="3" y="5" width="18" height="14" rx="2" />
                        <path d="M12 5v14M7 12h10" />
                        <circle cx="12" cy="12" r="2" />
                    </svg>
                </span>
                <span class="ml-2 hidden sm:inline">Canchas</span>
            </button>
        </li>

        {{-- RESERVAS --}}
        <li class="block">
            <button type="button"
                data-section-target="reservas"
                class="panel-tab flex items-center h-9 sm:h-10 leading-10 px-2 sm:px-4 rounded-md sm:mx-1 cursor-pointer
                           transition-colors duration-100
                           bg-white text-gray-900 hover:bg-gray-100">
                <span class="text-xl">
                    {{-- Icono calendario --}}
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-900" viewBox="0 0 24 24"
                        fill="none" stroke="currentColor" stroke-width="1.7"
                        stroke-linecap="round" stroke-linejoin="round">
                        <rect x="3" y="4" width="18" height="17" rx="2" />
                        <path d="M8 2v4M16 2v4M3 10h18" />
                    </svg>
                </span>
                <span class="ml-2 hidden sm:inline">Reservas</span>
            </button>
        </li>

        {{-- CALENDARIO --}}
        <li class="block">
            <button type="button"
                data-section-target="calendario"
                class="panel-tab flex items-center h-9 sm:h-10 leading-10 px-2 sm:px-4 rounded-md sm:mx-1 cursor-pointer
                           transition-colors duration-100
                           bg-white text-gray-900 hover:bg-gray-100">
                <span class="text-xl">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-900" viewBox="0 0 24 24"
                        fill="none" stroke="currentColor" stroke-width="1.7"
                        stroke-linecap="round" stroke-linejoin="round">
                        <path d="M3 4h18v17H3z" />
                        <path d="M8 2v4M16 2v4M3 10h18" />
                        <path d="M7 14h4v4H7z" />
                    </svg>
                </span>
                <span class="ml-2 hidden sm:inline">Calendario</span>
            </button>
        </li>

        {{-- BLOQUEOS --}}
        <li class="block">
            <button type="button"
                data-section-target="bloqueos"
                class="panel-tab flex items-center h-9 sm:h-10 leading-10 px-2 sm:px-4 rounded-md sm:mx-1 cursor-pointer
                           transition-colors duration-100
                           bg-white text-gray-900 hover:bg-gray-100">
                <span class="text-xl">
                    {{-- Icono candado --}}
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-900" viewBox="0 0 24 24"
                        fill="none" stroke="currentColor" stroke-width="1.7"
                        stroke-linecap="round" stroke-linejoin="round">
                        <rect x="4" y="10" width="16" height="10" rx="2" />
                        <path d="M8 10V8a4 4 0 118 0v2" />
                        <circle cx="12" cy="15" r="1.4" />
                    </svg>
                </span>
                <span class="ml-2 hidden sm:inline">Bloqueos</span>
            </button>
        </li>

        {{-- PRECIOS --}}
        <li class="block">
            <button type="button"
                data-section-target="precios"
                class="panel-tab flex items-center h-9 sm:h-10 leading-10 px-2 sm:px-4 rounded-md sm:mx-1 cursor-pointer
                           transition-colors duration-100
                           bg-white text-gray-900 hover:bg-gray-100">
                <span class="text-xl">
                    {{-- Nuevo icono dólar bonito --}}
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-900" viewBox="0 0 24 24"
                        fill="none" stroke="currentColor" stroke-width="1.7"
                        stroke-linecap="round" stroke-linejoin="round">
                        <!-- Línea superior e inferior -->
                        <path d="M12 3v3" />
                        <path d="M12 18v3" />
                        <!-- Curva del símbolo $ -->
                        <path d="M9 8.5C9 7.1 10.2 6 12 6s3 1.1 3 2.5S13.8 11 12 11s-3 1.1-3 2.5S10.2 16 12 16s3-1.1 3-2.5" />
                    </svg>
                </span>
                <span class="ml-2 hidden sm:inline">Precios</span>
            </button>
        </li>

        {{-- USUARIOS --}}
        <li class="block">
            <button type="button"
                data-section-target="usuarios"
                class="panel-tab flex items-center h-9 sm:h-10 leading-10 px-2 sm:px-4 rounded-md sm:mx-1 cursor-pointer
                           transition-colors duration-100
                           bg-white text-gray-900 hover:bg-gray-100">
                <span class="text-xl">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-900" viewBox="0 0 24 24"
                        fill="none" stroke="currentColor" stroke-width="1.7"
                        stroke-linecap="round" stroke-linejoin="round">
                        <path d="M16 21v-2a4 4 0 00-4-4H7a4 4 0 00-4 4v2" />
                        <circle cx="9" cy="7" r="3" />
                        <path d="M21 21v-2a4 4 0 00-3-3.87" />
                        <path d="M16 3.13a4 4 0 010 7.75" />
                    </svg>
                </span>
                <span class="ml-2 hidden sm:inline">Usuarios</span>
            </button>
        </li>

    </ul>
</nav>



<div>
    <!-- SECCIÓN CANCHAS -->
    <section id="canchas" data-section="canchas" class="scroll-mt-32">
        <div class="bg-gradient-to-br from-emerald-950 via-emerald-900 to-emerald-800 min-h-screen flex justify-center p-4 sm:p-6 md:p-10">
            <div class="w-full max-w-5xl space-y-4">
                <!-- ENCABEZADO + BOTÓN -->
                <div class="flex flex-col sm:flex-row gap-3 sm:items-center sm:justify-between">
                    <div>
                        <h1 class="text-lg sm:text-xl font-bold text-white">Canchas registradas</h1>
                    </div>

                    <button id="abrirModal" type="button"
                        class="flex items-center justify-center gap-2 bg-green-600 hover:bg-green-700 text-white font-semibold px-4 py-2 rounded-lg shadow-md transition whitespace-nowrap">
                        <span class="text-xl font-bold">+</span>
                        <span class="hidden xs:inline">AGREGAR</span>
                        <span class="xs:hidden">NUEVA</span>
                    </button>
                </div>

                <div class="bg-emerald-950/60 border border-emerald-800 rounded-2xl p-4 shadow-2xl">
                    <div class="flex flex-col gap-4 md:flex-row md:items-end">
                        <div class="flex-1">
                            <label for="cancha-search" class="text-xs uppercase tracking-[0.3em] text-emerald-400">Buscar por nombre</label>
                            <div class="relative mt-1">
                                <span class="absolute inset-y-0 left-3 flex items-center text-emerald-500">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                                        stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-4.35-4.35m0 0A7 7 0 1 0 6.5 6.5a7 7 0 0 0 10.15 10.15z" />
                                    </svg>
                                </span>
                                <input id="cancha-search" type="search" placeholder="Ej. Arena Indoor"
                                    class="w-full rounded-xl border border-emerald-700 bg-emerald-900/70 pl-9 pr-3 py-2 text-sm text-white placeholder-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500"
                                    data-cancha-search>
                            </div>
                        </div>

                        <div class="w-full md:w-64">
                            <label for="cancha-type-filter" class="text-xs uppercase tracking-[0.3em] text-emerald-400">Filtrar por tipo</label>
                            <select id="cancha-type-filter"
                                class="mt-1 w-full rounded-xl border border-emerald-700 bg-emerald-900/70 px-3 py-2 text-sm text-white focus:outline-none focus:ring-2 focus:ring-emerald-500"
                                data-cancha-type-filter>
                                <option value="">Todos los tipos</option>
                                @foreach ($canchaTipos as $tipo)
                                    <option value="{{ $tipo }}">{{ $tipo }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <!-- VISTA DE TABLA (Desktop) -->
                <div class="hidden md:block overflow-x-auto rounded-2xl border border-emerald-800/60 bg-emerald-950/70 shadow-2xl">
                    <table class="w-full text-left text-sm text-gray-300">
                        <thead class="bg-emerald-950/60">
                            <tr class="uppercase text-xs text-emerald-400 tracking-wide">
                                <th class="px-6 py-3">Nombre</th>
                                <th class="px-6 py-3">Tipo</th>
                                <th class="px-6 py-3">Descripción</th>
                                <th class="px-6 py-3 text-right">Precio/Hora</th>
                                <th class="px-6 py-3">Imagen</th>
                                <th class="px-6 py-3 text-center">Activa</th>
                                <th class="px-6 py-3 text-right">Acciones</th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-emerald-900/50">
                            @forelse ($canchas as $cancha)
                                @php
                                    $rowIsEditing = (int) $editarCanchaId === $cancha->id;
                                    $imagenPath = $cancha->imagen_url ? ltrim($cancha->imagen_url, '/') : null;
                                @endphp
                                <tr class="hover:bg-emerald-900/40 transition-colors"
                                    data-cancha-row
                                    data-cancha-nombre="{{ mb_strtolower($cancha->nombre ?? '') }}"
                                    data-cancha-tipo="{{ mb_strtolower($cancha->tipo ?? '') }}">
                                    <td class="px-6 py-4 font-semibold text-white">
                                        {{ $cancha->nombre }}
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="inline-flex items-center rounded-full bg-emerald-500/15 text-emerald-200 px-3 py-1 text-xs font-medium">
                                            {{ $cancha->tipo }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 max-w-xs">
                                        <p class="text-sm text-gray-600">
                                            {{ $cancha->descripcion ?: 'Sin descripción registrada' }}
                                        </p>
                                    </td>
                                    <td class="px-6 py-4 text-right font-semibold text-emerald-400">
                                        ${{ number_format($cancha->precio_hora, 2, '.', ',') }}
                                    </td>
                                    <td class="px-6 py-4">
                                        @if ($imagenPath)
                                            <img src="{{ asset($imagenPath) }}" alt="Imagen de {{ $cancha->nombre }}"
                                                class="h-16 w-24 rounded-lg border border-gray-300 object-cover shadow">
                                        @else
                                            <span class="text-xs font-mono text-gray-400">
                                                Sin imagen
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        @if ($cancha->activa)
                                            <span class="inline-flex items-center justify-center bg-emerald-500/15 text-emerald-200 px-3 py-1 rounded-full text-xs font-medium">
                                                Disponible
                                            </span>
                                        @else
                                            <span class="inline-flex items-center justify-center bg-red-100 text-red-700 px-3 py-1 rounded-full text-xs font-medium">
                                                Inactiva
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center justify-end gap-3 text-lg text-gray-600">
                                            <button type="button"
                                                class="hover:text-green-600 transition-colors edit-toggle"
                                                data-edit-target="edit-modal-{{ $cancha->id }}"
                                                title="Editar cancha">
                                                <svg width="20" height="20" fill="currentColor" viewBox="0 0 24 24">
                                                    <path d="M3 17.25V21h3.75L17.81 9.94l-3.75-3.75L3 17.25zM21.41 6.34c.39-.39.39-1.02 0-1.41l-2.34-2.34a1 1 0 0 0-1.41 0l-1.83 1.83 3.75 3.75 1.83-1.83z" />
                                                </svg>
                                            </button>
                                            <form id="delete-form-{{ $cancha->id }}" method="POST"
                                                action="{{ route('admin.canchas.destroy', $cancha) }}">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button" class="hover:text-red-600 transition-colors"
                                                    data-delete-target="delete-form-{{ $cancha->id }}" title="Eliminar">
                                                    <svg width="20" height="20" fill="currentColor" viewBox="0 0 24 24">
                                                        <path d="M6 19a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2V7H6v12zm3-9h2v7H9V10zm4 0h2v7h-2v-7z" />
                                                        <path d="M15.5 4l-1-1h-5l-1 1H5v2h14V4z" />
                                                    </svg>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-6 py-10 text-center text-gray-500">
                                        Aún no hay canchas registradas.
                                    </td>
                                </tr>
                            @endforelse
                            @if ($canchas->isNotEmpty())
                                <tr class="hidden" data-cancha-empty-row>
                                    <td colspan="7" class="px-6 py-10 text-center text-emerald-400">
                                        No se encontraron canchas que coincidan con los filtros aplicados.
                                    </td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>

                <!-- VISTA DE TARJETAS (Móvil) -->
                <div class="md:hidden space-y-4">
                    @forelse ($canchas as $cancha)
                        @php
                            $imagenPath = $cancha->imagen_url ? ltrim($cancha->imagen_url, '/') : null;
                        @endphp
                        <div class="bg-white border border-green-100 rounded-xl p-4 shadow-lg hover:shadow-xl hover:border-green-200 transition-all">
                        <div class="bg-emerald-950/60 border border-emerald-800 rounded-xl p-4 shadow-xl"
                            data-cancha-card
                            data-cancha-nombre="{{ mb_strtolower($cancha->nombre ?? '') }}"
                            data-cancha-tipo="{{ mb_strtolower($cancha->tipo ?? '') }}">
                            <div class="flex items-start gap-4">
                                @if ($imagenPath)
                                    <img src="{{ asset($imagenPath) }}" alt="Imagen de {{ $cancha->nombre }}"
                                        class="h-20 w-28 rounded-lg border border-slate-800 object-cover shadow flex-shrink-0">
                                @else
                                    <div class="h-20 w-28 rounded-lg border border-slate-800 bg-slate-900 flex items-center justify-center flex-shrink-0">
                                        <span class="text-xs text-emerald-500">Sin imagen</span>
                                    </div>
                                @endif

                                <div class="flex-1 min-w-0">
                                    <div class="flex items-start justify-between gap-2">
                                        <h3 class="font-bold text-white text-base truncate">{{ $cancha->nombre }}</h3>
                                        @if ($cancha->activa)
                                            <span class="inline-flex items-center bg-green-500/15 text-green-400 px-2 py-0.5 rounded-full text-xs font-medium whitespace-nowrap">
                                                Activa
                                            </span>
                                        @else
                                            <span class="inline-flex items-center bg-red-500/10 text-red-300 px-2 py-0.5 rounded-full text-xs font-medium whitespace-nowrap">
                                                Inactiva
                                            </span>
                                        @endif
                                    </div>

                                    <span class="inline-flex items-center rounded-full bg-sky-500/10 text-sky-300 px-2 py-0.5 text-xs font-medium mt-1">
                                        {{ $cancha->tipo }}
                                    </span>

                                    <p class="text-xs text-emerald-400 mt-2 line-clamp-2">
                                        {{ $cancha->descripcion ?: 'Sin descripción registrada' }}
                                    </p>
                                </div>
                            </div>

                            <div class="flex items-center justify-between mt-4 pt-4 border-t border-slate-800">
                                <div class="font-semibold text-emerald-400">
                                    ${{ number_format($cancha->precio_hora, 2, '.', ',') }}<span class="text-xs text-emerald-400">/hora</span>
                                </div>

                                <div class="flex items-center gap-3">
                                    <button type="button"
                                        class="p-2 hover:bg-blue-500/10 hover:text-blue-400 rounded-lg transition-colors edit-toggle"
                                        data-edit-target="edit-modal-{{ $cancha->id }}"
                                        title="Editar cancha">
                                        <svg width="18" height="18" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M3 17.25V21h3.75L17.81 9.94l-3.75-3.75L3 17.25zM21.41 6.34c.39-.39.39-1.02 0-1.41l-2.34-2.34a1 1 0 0 0-1.41 0l-1.83 1.83 3.75 3.75 1.83-1.83z" />
                                        </svg>
                                    </button>
                                    <form id="delete-form-{{ $cancha->id }}" method="POST"
                                        action="{{ route('admin.canchas.destroy', $cancha) }}">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" class="p-2 hover:bg-red-500/10 hover:text-red-400 rounded-lg transition-colors"
                                            data-delete-target="delete-form-{{ $cancha->id }}" title="Eliminar">
                                            <svg width="18" height="18" fill="currentColor" viewBox="0 0 24 24">
                                                <path d="M6 19a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2V7H6v12zm3-9h2v7H9V10zm4 0h2v7h-2v-7z" />
                                                <path d="M15.5 4l-1-1h-5l-1 1H5v2h14V4z" />
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="bg-emerald-950/60 border border-emerald-800 rounded-xl p-8 text-center text-emerald-400">
                            Aún no hay canchas registradas.
                        </div>
                    @endforelse
                    @if ($canchas->isNotEmpty())
                        <div class="hidden rounded-xl border border-slate-800 bg-slate-950/60 p-6 text-center text-emerald-400"
                            data-cancha-empty-mobile>
                            No se encontraron canchas que coincidan con los filtros aplicados.
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- MODAL PARA CREAR CANCHA -->
        <div id="modal"
            class="fixed inset-0 bg-black/60 backdrop-blur-sm hidden items-center justify-center z-50 px-4 py-4 sm:py-8">
            <div
                class="relative w-full max-w-5xl border border-slate-800 bg-slate-950/95 p-4 sm:p-6 md:p-8 shadow-2xl max-h-[90vh] overflow-y-auto">
                <button type="button" data-modal-close
                    class="absolute top-2 right-2 sm:top-4 sm:right-4 bg-blue-500 text-white w-8 h-8 sm:w-10 sm:h-10 rounded-full shadow-lg flex items-center justify-center text-xl sm:text-2xl font-bold hover:bg-red-500 transition">
                    ✕
                </button>

                <div class="space-y-4 sm:space-y-6 text-white">
                    <div>
                        <p class="text-xs sm:text-sm uppercase tracking-[0.2em] sm:tracking-[0.3em] text-blue-300">Nueva cancha</p>
                        <h2 class="text-xl sm:text-2xl font-semibold">Registrar una cancha</h2>
                        <p class="text-emerald-400 text-xs sm:text-sm">Configura los datos básicos para que pueda reservarse en el
                            sistema.</p>
                    </div>

                    <form method="POST" action="{{ route('admin.canchas.store') }}" enctype="multipart/form-data"
                        class="grid gap-4 md:grid-cols-2 text-sm">
                        @csrf

                        <div>
                            <label class="block text-xs uppercase tracking-widest text-emerald-400 mb-1">Nombre</label>
                            <input type="text" name="nombre" value="{{ old('nombre') }}"
                                class="w-full rounded-lg border border-slate-600 bg-slate-900/70 px-3 py-2 focus:outline-none focus:ring focus:ring-emerald-500"
                                required>
                            @error('nombre', 'crearCancha')
                                <p class="text-xs text-red-300 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-xs uppercase tracking-widest text-emerald-400 mb-1">Tipo</label>
                            <select name="tipo"
                                class="w-full rounded-lg border border-slate-600 bg-slate-900/70 px-3 py-2 focus:outline-none focus:ring focus:ring-emerald-500"
                                required>
                                <option value="" disabled {{ old('tipo') ? '' : 'selected' }}>Selecciona un tipo</option>
                                <option value="Arena Indoor" {{ old('tipo') === 'Arena Indoor' ? 'selected' : '' }}>Arena Indoor</option>
                                <option value="Estadio Chico" {{ old('tipo') === 'Estadio Chico' ? 'selected' : '' }}>Estadio Chico</option>
                                <option value="Cancha Central" {{ old('tipo') === 'Cancha Central' ? 'selected' : '' }}>Cancha Central</option>
                            </select>
                            @error('tipo', 'crearCancha')
                                <p class="text-xs text-red-300 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-xs uppercase tracking-widest text-emerald-400 mb-1">Descripción</label>
                            <textarea name="descripcion" rows="3"
                                class="w-full rounded-lg border border-slate-600 bg-slate-900/70 px-3 py-2 focus:outline-none focus:ring focus:ring-emerald-500"
                                placeholder="Cuenta qué hace especial a la cancha">{{ old('descripcion') }}</textarea>
                            @error('descripcion', 'crearCancha')
                                <p class="text-xs text-red-300 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-xs uppercase tracking-widest text-emerald-400 mb-1">Precio por hora</label>
                            <input type="number" name="precio_hora" min="0" step="0.01" value="{{ old('precio_hora') }}"
                                class="w-full rounded-lg border border-slate-600 bg-slate-900/70 px-3 py-2 focus:outline-none focus:ring focus:ring-emerald-500"
                                required>
                            @error('precio_hora', 'crearCancha')
                                <p class="text-xs text-red-300 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-xs uppercase tracking-widest text-emerald-400 mb-1">Imagen</label>
                            <input type="file" name="imagen" accept="image/*"
                                class="w-full rounded-lg border border-slate-600 bg-slate-900/70 px-3 py-2 text-sm file:bg-slate-800 file:text-slate-200 file:border-0 file:px-4 file:py-2 focus:outline-none focus:ring focus:ring-emerald-500"
                                required>
                            @error('imagen', 'crearCancha')
                                <p class="text-xs text-red-300 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex items-center gap-3">
                            <input type="hidden" name="activa" value="0">
                            <input type="checkbox" id="modal_activa" name="activa" value="1"
                                class="h-4 w-4 rounded border-slate-500 bg-slate-900 text-blue-500 focus:ring-emerald-500"
                                {{ old('activa') ? 'checked' : '' }}>
                            <label for="modal_activa" class="text-xs uppercase tracking-widest text-emerald-400">Activa para
                                reservas</label>
                        </div>
                        @error('activa', 'crearCancha')
                            <div class="md:col-span-2 text-xs text-red-300">
                                {{ $message }}
                            </div>
                        @enderror

                        <div class="md:col-span-2 flex justify-end gap-3">
                            <button type="button" data-modal-close
                                class="px-4 py-2 rounded-lg border border-slate-600 text-slate-300 hover:bg-slate-800 transition">Cancelar</button>
                            <button type="submit"
                                class="px-5 py-2 rounded-lg bg-green-600 hover:bg-green-700 text-white font-semibold transition">Guardar
                                cancha</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- MODALES PARA EDITAR CANCHAS -->
        @foreach ($canchas as $cancha)
            @php
                $rowIsEditing = (int) $editarCanchaId === $cancha->id;
                $editNombre = $rowIsEditing ? old('nombre', $cancha->nombre) : $cancha->nombre;
                $editTipo = $rowIsEditing ? old('tipo', $cancha->tipo) : $cancha->tipo;
                $editDescripcion = $rowIsEditing ? old('descripcion', $cancha->descripcion) : $cancha->descripcion;
                $editPrecio = $rowIsEditing ? old('precio_hora', $cancha->precio_hora) : $cancha->precio_hora;
                $editActiva = $rowIsEditing ? (bool) old('activa', $cancha->activa) : (bool) $cancha->activa;
            @endphp
            <div id="edit-modal-{{ $cancha->id }}"
                class="edit-modal fixed inset-0 bg-black/60 backdrop-blur-sm hidden items-center justify-center z-50 px-4 py-4 sm:py-8"
                data-edit-modal data-open-default="{{ $rowIsEditing ? 'true' : 'false' }}">
                <div
                    class="relative w-full max-w-5xl border border-slate-800 bg-slate-950/95 p-4 sm:p-6 md:p-8 shadow-2xl max-h-[90vh] overflow-y-auto text-white">
                    <button type="button" data-edit-modal-close
                        class="absolute top-2 right-2 sm:top-4 sm:right-4 bg-blue-500 text-white w-8 h-8 sm:w-10 sm:h-10 rounded-full shadow-lg flex items-center justify-center text-xl sm:text-2xl font-bold hover:bg-red-500 transition">
                        ✕
                    </button>

                    <div class="space-y-4 sm:space-y-6">
                        <div>
                            <p class="text-xs sm:text-sm uppercase tracking-[0.2em] sm:tracking-[0.3em] text-blue-300">Editar cancha</p>
                            <h2 class="text-xl sm:text-2xl font-semibold">Actualizar {{ $cancha->nombre }}</h2>
                            <p class="text-emerald-400 text-xs sm:text-sm">Modifica la información y guarda los cambios.</p>
                        </div>

                        <form method="POST" action="{{ route('admin.canchas.update', $cancha) }}" enctype="multipart/form-data"
                            class="grid gap-4 md:grid-cols-2 text-sm">
                            @csrf
                            @method('PUT')
                            <div>
                                <label class="block text-xs uppercase tracking-widest text-emerald-400 mb-1">Nombre</label>
                                <input type="text" name="nombre" value="{{ $editNombre }}"
                                    class="w-full rounded-lg border border-slate-600 bg-slate-900/70 px-3 py-2 focus:outline-none focus:ring focus:ring-emerald-500"
                                    required>
                                @if ($rowIsEditing && $errors->editarCancha->has('nombre'))
                                    <p class="text-xs text-red-300 mt-1">{{ $errors->editarCancha->first('nombre') }}</p>
                                @endif
                            </div>
                            <div>
                                <label class="block text-xs uppercase tracking-widest text-emerald-400 mb-1">Tipo</label>
                                <select name="tipo"
                                    class="w-full rounded-lg border border-slate-600 bg-slate-900/70 px-3 py-2 focus:outline-none focus:ring focus:ring-emerald-500"
                                    required>
                                    <option value="" disabled {{ $editTipo ? '' : 'selected' }}>Selecciona un tipo</option>
                                    <option value="Arena Indoor" {{ $editTipo === 'Arena Indoor' ? 'selected' : '' }}>Arena Indoor</option>
                                    <option value="Estadio Chico" {{ $editTipo === 'Estadio Chico' ? 'selected' : '' }}>Estadio Chico</option>
                                    <option value="Cancha Central" {{ $editTipo === 'Cancha Central' ? 'selected' : '' }}>Cancha Central</option>
                                </select>
                                @if ($rowIsEditing && $errors->editarCancha->has('tipo'))
                                    <p class="text-xs text-red-300 mt-1">{{ $errors->editarCancha->first('tipo') }}</p>
                                @endif
                            </div>
                            <div class="md:col-span-2">
                                <label class="block text-xs uppercase tracking-widest text-emerald-400 mb-1">Descripción</label>
                                <textarea name="descripcion" rows="3"
                                    class="w-full rounded-lg border border-slate-600 bg-slate-900/70 px-3 py-2 focus:outline-none focus:ring focus:ring-emerald-500">{{ $editDescripcion }}</textarea>
                                @if ($rowIsEditing && $errors->editarCancha->has('descripcion'))
                                    <p class="text-xs text-red-300 mt-1">{{ $errors->editarCancha->first('descripcion') }}</p>
                                @endif
                            </div>
                            <div>
                                <label class="block text-xs uppercase tracking-widest text-emerald-400 mb-1">Precio por hora</label>
                                <input type="number" min="0" step="0.01" name="precio_hora" value="{{ $editPrecio }}"
                                    class="w-full rounded-lg border border-slate-600 bg-slate-900/70 px-3 py-2 focus:outline-none focus:ring focus:ring-emerald-500"
                                    required>
                                @if ($rowIsEditing && $errors->editarCancha->has('precio_hora'))
                                    <p class="text-xs text-red-300 mt-1">{{ $errors->editarCancha->first('precio_hora') }}</p>
                                @endif
                            </div>
                            <div>
                                <label class="block text-xs uppercase tracking-widest text-emerald-400 mb-1">Imagen actual</label>
                                @if ($cancha->imagen_url)
                                    <img src="{{ asset(ltrim($cancha->imagen_url, '/')) }}" alt="Imagen de {{ $cancha->nombre }}"
                                        class="h-20 w-32 rounded-lg border border-slate-700 object-cover">
                                @else
                                    <p class="text-xs text-emerald-400">Sin imagen cargada.</p>
                                @endif
                            </div>
                            <div>
                                <label class="block text-xs uppercase tracking-widest text-emerald-400 mb-1">Actualizar imagen</label>
                                <input type="file" name="imagen" accept="image/*"
                                    class="w-full rounded-lg border border-slate-600 bg-slate-900/70 px-3 py-2 text-sm file:bg-slate-800 file:text-slate-200 file:border-0 file:px-4 file:py-2 focus:outline-none focus:ring focus:ring-emerald-500">
                                @if ($rowIsEditing && $errors->editarCancha->has('imagen'))
                                    <p class="text-xs text-red-300 mt-1">{{ $errors->editarCancha->first('imagen') }}</p>
                                @endif
                            </div>

                            <div class="md:col-span-2 border-t border-slate-800 pt-4 mt-2">
                                <h3 class="text-sm font-semibold mb-3 text-blue-300 uppercase tracking-widest">Galería de Imágenes</h3>

                                @if($cancha->imagenes->count() > 0)
                                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mb-4">
                                        @foreach($cancha->imagenes as $img)
                                            <div class="relative group">
                                                <img src="{{ asset($img->imagen_url) }}" class="w-full h-24 object-cover rounded-lg border border-slate-700">
                                                <button type="button"
                                                        onclick="if(confirm('¿Eliminar imagen?')) document.getElementById('delete-img-{{ $img->id }}').submit()"
                                                        class="absolute top-1 right-1 bg-red-500 text-white rounded-full p-1 opacity-0 group-hover:opacity-100 transition-opacity hover:bg-red-600">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                                                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                                                    </svg>
                                                </button>
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <p class="text-xs text-emerald-500 mb-4 italic">No hay imágenes adicionales en la galería.</p>
                                @endif

                                <div>
                                    <label class="block text-xs uppercase tracking-widest text-emerald-400 mb-1">Agregar más fotos</label>
                                    <input type="file" name="imagenes_galeria[]" multiple accept="image/*"
                                        class="w-full rounded-lg border border-slate-600 bg-slate-900/70 px-3 py-2 text-sm file:bg-slate-800 file:text-slate-200 file:border-0 file:px-4 file:py-2 focus:outline-none focus:ring focus:ring-emerald-500">
                                    <p class="text-xs text-emerald-500 mt-1">Puedes seleccionar varias imágenes a la vez (Ctrl + Click).</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-3">
                                <input type="hidden" name="activa" value="0">
                                <input type="checkbox" id="modal_activa_{{ $cancha->id }}" name="activa" value="1"
                                    class="h-4 w-4 rounded border-slate-500 bg-slate-900 text-blue-500 focus:ring-emerald-500"
                                    {{ $editActiva ? 'checked' : '' }}>
                                <label for="modal_activa_{{ $cancha->id }}"
                                    class="text-xs uppercase tracking-widest text-emerald-400">Activa para reservas</label>
                            </div>
                            @if ($rowIsEditing && $errors->editarCancha->has('activa'))
                                <div class="md:col-span-2 text-xs text-red-300">
                                    {{ $errors->editarCancha->first('activa') }}
                                </div>
                            @endif
                            <div class="md:col-span-2 flex justify-end gap-3">
                                <button type="button" data-edit-modal-close
                                    class="px-4 py-2 rounded-lg border border-slate-600 text-slate-300 hover:bg-slate-800 transition">Cancelar</button>
                                <button type="submit"
                                    class="px-5 py-2 rounded-lg bg-green-600 hover:bg-green-700 text-white font-semibold transition">
                                    Guardar cambios
                                </button>
                            </div>
                        </form>

                        @foreach($cancha->imagenes as $img)
                            <form id="delete-img-{{ $img->id }}" action="{{ route('admin.canchas.imagenes.destroy', $img->id) }}" method="POST" style="display: none;">
                                @csrf
                                @method('DELETE')
                            </form>
                        @endforeach
                    </div>
                </div>
            </div>
        @endforeach

        <div id="feedback-modal"
            class="fixed inset-0 bg-black/60 backdrop-blur-sm hidden items-center justify-center z-50 px-4 py-4 sm:py-8"
            data-feedback-modal data-feedback-visible="{{ $feedbackMessage ? 'true' : 'false' }}"
            data-feedback-type="{{ $feedbackType ?? 'success' }}">
            <div
                class="relative w-full max-w-lg border {{ $isErrorFeedback ? 'border-red-500/40' : 'border-emerald-500/40' }} bg-slate-950/95 p-4 sm:p-6 shadow-2xl text-white">
                <button type="button" data-feedback-close
                    class="absolute top-2 right-2 sm:top-3 sm:right-3 text-emerald-400 hover:text-white transition" aria-label="Cerrar">
                    ✕
                </button>
                <div class="space-y-3 sm:space-y-4">
                    <div class="flex items-start gap-3">
                        <div
                            class="rounded-full p-2 {{ $isErrorFeedback ? 'bg-red-500/15 text-red-300' : 'bg-emerald-500/15 text-emerald-300' }}">
                            @if ($isErrorFeedback)
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" stroke="currentColor"
                                    stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M12 9v4" />
                                    <path d="M12 17h.01" />
                                    <path d="M10 3h4l7 12-2 4H5l-2-4 7-12z" />
                                </svg>
                            @else
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" stroke="currentColor"
                                    stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M5 13l4 4L19 7" />
                                </svg>
                            @endif
                        </div>
                        <div>
                            <p class="text-sm uppercase tracking-[0.3em] text-emerald-400">{{ $feedbackTitle }}</p>
                            <p class="text-lg font-semibold text-white">{{ $feedbackMessage }}</p>
                        </div>
                    </div>

                    <div class="flex justify-end gap-3">
                        <button type="button" data-feedback-close
                            class="px-5 py-2 rounded-lg font-semibold transition bg-red-500 hover:bg-red-600 text-white {{ $isErrorFeedback ? '' : 'hidden' }}">
                            Cancelar
                        </button>
                        <button type="button" data-feedback-close
                            class="px-5 py-2 rounded-lg font-semibold transition bg-green-600 hover:bg-green-700 text-slate-900 {{ $isErrorFeedback ? 'hidden' : '' }}">
                            Aceptar
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div id="delete-modal"
            class="fixed inset-0 bg-black/70 backdrop-blur-sm hidden items-center justify-center z-50 px-4 py-4 sm:py-8">
            <div class="relative w-full max-w-lg border border-red-500/40 bg-slate-950/95 p-4 sm:p-6 shadow-2xl text-white">
                <button type="button" data-delete-cancel
                    class="absolute top-2 right-2 sm:top-3 sm:right-3 text-emerald-400 hover:text-white transition" aria-label="Cerrar">
                    ✕
                </button>
                <div class="space-y-3 sm:space-y-4">
                    <div class="flex items-start gap-3">
                        <div class="rounded-full p-2 bg-red-500/15 text-red-300">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" stroke="currentColor"
                                stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M12 9v4" />
                                <path d="M12 17h.01" />
                                <path d="M10 3h4l7 12-2 4H5l-2-4 7-12z" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm uppercase tracking-[0.3em] text-red-300">Alerta</p>
                            <p class="text-lg font-semibold text-white">
                                Si elimina esta cancha se eliminarán todas las reservas asociadas a esta cancha.
                            </p>
                        </div>
                    </div>

                    <div class="flex justify-end gap-3">
                        <button type="button" data-delete-cancel
                            class="px-4 py-2 rounded-lg border border-slate-600 text-slate-300 hover:bg-slate-800 transition">
                            Cancelar
                        </button>
                        <button type="button" data-delete-confirm
                            class="px-5 py-2 rounded-lg bg-red-500 hover:bg-red-600 text-white font-semibold transition">
                            Aceptar
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- SECCIÓN CALENDARIO -->
    <section id="calendario" data-section="calendario" class="scroll-mt-32 hidden">
        <div class="bg-gradient-to-br from-emerald-950 via-emerald-900 to-emerald-800 min-h-screen flex justify-center p-4 sm:p-6 md:p-10">
            <div class="w-full max-w-6xl space-y-6">
                <livewire:admin-calendar />
            </div>
        </div>
    </section>

    <!-- SECCIÓN RESERVAS -->
    <section id="reservas" data-section="reservas" class="scroll-mt-32 hidden">
        <div class="bg-gradient-to-br from-emerald-950 via-emerald-900 to-emerald-800 min-h-screen flex justify-center p-4 sm:p-6 md:p-10">
            <div class="w-full max-w-6xl space-y-4">
                <div class="flex flex-col gap-2 sm:gap-3 md:flex-row md:items-center md:justify-between">
                    <div>
                        <h1 class="text-lg sm:text-xl font-bold text-white">Reservas registradas</h1>
                        <p class="text-xs sm:text-sm text-gray-600">Consulta el detalle de cada reserva creada en el sistema.</p>
                    </div>
                    <div class="text-xs sm:text-sm text-emerald-400">
                        Total: <span class="font-semibold text-white">{{ $reservas->count() }}</span>
                    </div>
                </div>

                <div class="overflow-x-auto rounded-2xl border border-emerald-800/60 bg-emerald-950/70 shadow-2xl">
                <div class="bg-emerald-950/60 border border-emerald-800 rounded-2xl p-4 shadow-2xl">
                    <div class="grid gap-4 md:grid-cols-3">
                        <div>
                            <label for="reserva-cancha-search"
                                class="text-xs uppercase tracking-[0.3em] text-emerald-400">Buscar por cancha</label>
                            <div class="relative mt-1">
                                <span class="absolute inset-y-0 left-3 flex items-center text-emerald-500">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                                        stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="m21 21-4.35-4.35m0 0A7 7 0 1 0 6.5 6.5a7 7 0 0 0 10.15 10.15z" />
                                    </svg>
                                </span>
                                <input id="reserva-cancha-search" type="search" placeholder="Ej. Cancha Central"
                                    class="w-full rounded-xl border border-emerald-700 bg-emerald-900/70 pl-9 pr-3 py-2 text-sm text-white placeholder-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500"
                                    data-reserva-cancha-search>
                            </div>
                        </div>

                        <div>
                            <label for="reserva-tipo-filter" class="text-xs uppercase tracking-[0.3em] text-emerald-400">Filtrar por tipo</label>
                            <select id="reserva-tipo-filter"
                                class="mt-1 w-full rounded-xl border border-emerald-700 bg-emerald-900/70 px-3 py-2 text-sm text-white focus:outline-none focus:ring-2 focus:ring-emerald-500"
                                data-reserva-tipo-filter>
                                <option value="">Todos los tipos</option>
                                @foreach ($reservaTipos as $tipo)
                                    <option value="{{ $tipo }}">{{ $tipo }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label for="reserva-cliente-search"
                                class="text-xs uppercase tracking-[0.3em] text-emerald-400">Buscar por cliente</label>
                            <div class="relative mt-1">
                                <span class="absolute inset-y-0 left-3 flex items-center text-emerald-500">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                                        stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="m21 21-4.35-4.35m0 0A7 7 0 1 0 6.5 6.5a7 7 0 0 0 10.15 10.15z" />
                                    </svg>
                                </span>
                                <input id="reserva-cliente-search" type="search" placeholder="Ej. Juan Pérez"
                                    class="w-full rounded-xl border border-emerald-700 bg-emerald-900/70 pl-9 pr-3 py-2 text-sm text-white placeholder-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500"
                                    data-reserva-cliente-search>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="overflow-x-auto rounded-2xl border border-slate-800 bg-slate-950/60 shadow-2xl">
                    <table class="w-full text-left text-sm text-gray-300">
                        <thead class="bg-emerald-950/60">
                            <tr class="uppercase text-xs text-emerald-400 tracking-wide">
                                <th class="px-6 py-3">Cancha</th>
                                <th class="px-6 py-3">Cliente</th>
                                <th class="px-6 py-3">Fecha reserva</th>
                                <th class="px-6 py-3">Inicio</th>
                                <th class="px-6 py-3">Fin</th>
                                <th class="px-6 py-3">Duración</th>
                                <th class="px-6 py-3 text-right">Total</th>
                                <th class="px-6 py-3 text-center">Estado</th>
                                <th class="px-6 py-3">Creado por</th>
                                <th class="px-6 py-3">Actualizado por</th>
                                <th class="px-6 py-3 text-right">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-emerald-900/50">
                            @forelse ($reservas as $reserva)
                                @php
                                    $estadoColors = [
                                        'pendiente' => 'bg-amber-500/15 text-amber-300',
                                        'confirmada' => 'bg-emerald-500/15 text-emerald-300',
                                        'finalizada' => 'bg-blue-500/15 text-blue-300',
                                        'cancelada' => 'bg-rose-500/15 text-rose-300',
                                    ];
                                    $estadoClass = $estadoColors[$reserva->estado] ?? 'bg-slate-500/20 text-slate-200';
                                @endphp
                                <tr class="hover:bg-emerald-900/40 transition-colors"
                                    data-reserva-row
                                    data-reserva-cancha="{{ mb_strtolower(optional($reserva->cancha)->nombre ?? '') }}"
                                    data-reserva-tipo="{{ mb_strtolower(optional($reserva->cancha)->tipo ?? '') }}"
                                    data-reserva-cliente="{{ mb_strtolower(optional($reserva->cliente)->nombre ?? '') }}">
                                    <td class="px-6 py-4">
                                        <p class="font-semibold text-white">
                                            {{ optional($reserva->cancha)->nombre ?? 'Cancha eliminada' }}
                                        </p>
                                        <p class="text-xs text-emerald-500">Reserva #{{ $reserva->id }}</p>
                                    </td>
                                    <td class="px-6 py-4">
                                        <p class="font-semibold text-white">
                                            {{ optional($reserva->cliente)->nombre ?? 'Cliente no disponible' }}
                                        </p>
                                        <p class="text-xs text-emerald-400">
                                            {{ optional($reserva->cliente)->telefono ?? optional($reserva->cliente)->email ?? '—' }}
                                        </p>
                                    </td>
                                    <td class="px-6 py-4">
                                        <p class="font-semibold text-slate-200">
                                            {{ optional($reserva->fecha_reserva)->format('d/m/Y') ?? '—' }}
                                        </p>
                                    </td>
                                    <td class="px-6 py-4">
                                        <p class="font-semibold text-emerald-300">
                                            {{ optional($reserva->fecha_inicio)->format('d/m/Y H:i') ?? '—' }}
                                        </p>
                                    </td>
                                    <td class="px-6 py-4">
                                        <p class="font-semibold text-rose-200">
                                            {{ optional($reserva->fecha_fin)->format('d/m/Y H:i') ?? '—' }}
                                        </p>
                                    </td>
                                    <td class="px-6 py-4">
                                        <p class="font-semibold text-slate-200">
                                            {{ ! is_null($reserva->duracion_minutos) ? $reserva->duracion_minutos . ' min' : '—' }}
                                        </p>
                                    </td>
                                    <td class="px-6 py-4 text-right font-semibold text-emerald-400">
                                        @if (! is_null($reserva->total))
                                            ${{ number_format((float) $reserva->total, 2, '.', ',') }}
                                        @else
                                            —
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <span class="inline-flex items-center justify-center rounded-full px-3 py-1 text-xs font-medium {{ $estadoClass }}">
                                            {{ $reserva->estado ? ucfirst($reserva->estado) : 'Sin estado' }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <p class="font-semibold text-white">
                                            {{ optional($reserva->creador)->name ?? 'Usuario no disponible' }}
                                        </p>
                                        <p class="text-xs text-emerald-400">
                                            {{ optional($reserva->creador)->email ?? '—' }}
                                        </p>
                                    </td>
                                    <td class="px-6 py-4">
                                        <p class="font-semibold text-white">
                                            {{ optional($reserva->actualizador)->name ?? 'Usuario no disponible' }}
                                        </p>
                                        <p class="text-xs text-emerald-400">
                                            {{ optional($reserva->actualizador)->email ?? '—' }}
                                        </p>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center justify-end gap-3 text-lg">
                                            <button type="button"
                                                class="hover:text-blue-300 transition-colors"
                                                data-reserva-edit-target="reserva-edit-modal-{{ $reserva->id }}"
                                                title="Editar reserva">
                                                <svg width="20" height="20" fill="currentColor" viewBox="0 0 24 24">
                                                    <path d="M3 17.25V21h3.75L17.81 9.94l-3.75-3.75L3 17.25zM21.41 6.34c.39-.39.39-1.02 0-1.41l-2.34-2.34a1 1 0 0 0-1.41 0l-1.83 1.83 3.75 3.75 1.83-1.83z" />
                                                </svg>
                                            </button>
                                            <button type="button"
                                                class="hover:text-rose-400 transition-colors"
                                                data-reserva-delete-target="reserva-delete-form-{{ $reserva->id }}"
                                                data-reserva-delete-id="{{ $reserva->id }}"
                                                data-reserva-delete-name="{{ optional($reserva->cliente)->nombre ?? 'Cliente no disponible' }}"
                                                title="Eliminar reserva">
                                                <svg width="20" height="20" fill="currentColor" viewBox="0 0 24 24">
                                                    <path d="M6 19a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2V7H6v12zm3-9h2v7H9V10zm4 0h2v7h-2v-7z" />
                                                    <path d="M15.5 4l-1-1h-5l-1 1H5v2h14V4z" />
                                                </svg>
                                            </button>
                                        </div>
                                        <form id="reserva-delete-form-{{ $reserva->id }}" method="POST"
                                            action="{{ route('admin.reservas.destroy', $reserva) }}">
                                            @csrf
                                            @method('DELETE')
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="11" class="px-6 py-10 text-center text-emerald-400">
                                        Aún no hay reservas registradas.
                                    </td>
                                </tr>
                            @endforelse
                            @if ($reservas->isNotEmpty())
                                <tr class="hidden" data-reserva-empty-row>
                                    <td colspan="11" class="px-6 py-10 text-center text-emerald-400">
                                        No se encontraron reservas que coincidan con los filtros aplicados.
                                    </td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <!-- MODALES EDITAR RESERVA -->
        @foreach ($reservas as $reserva)
            @php
                $reservaRowEditing = (int) $editarReservaId === $reserva->id;
                $editCanchaId = $reservaRowEditing ? (int) old('cancha_id', $reserva->cancha_id) : $reserva->cancha_id;
                $editClienteId = $reservaRowEditing ? (int) old('cliente_id', $reserva->cliente_id) : $reserva->cliente_id;
                $editFechaReserva = $reservaRowEditing ? old('fecha_reserva', optional($reserva->fecha_reserva)->format('Y-m-d')) : optional($reserva->fecha_reserva)->format('Y-m-d');
                $editFechaInicio = $reservaRowEditing ? old('fecha_inicio', optional($reserva->fecha_inicio)->format('Y-m-d\\TH:i')) : optional($reserva->fecha_inicio)->format('Y-m-d\\TH:i');
                $editFechaFin = $reservaRowEditing ? old('fecha_fin', optional($reserva->fecha_fin)->format('Y-m-d\\TH:i')) : optional($reserva->fecha_fin)->format('Y-m-d\\TH:i');
                $editDuracion = $reservaRowEditing ? old('duracion_minutos', $reserva->duracion_minutos) : $reserva->duracion_minutos;
                $editPrecioHora = $reservaRowEditing ? old('precio_hora', $reserva->precio_hora) : $reserva->precio_hora;
                $editTotal = $reservaRowEditing ? old('total', $reserva->total) : $reserva->total;
                $editEstado = $reservaRowEditing ? old('estado', $reserva->estado) : $reserva->estado;
                $editObservaciones = $reservaRowEditing ? old('observaciones', $reserva->observaciones) : $reserva->observaciones;
                $editCreadoPor = $reservaRowEditing ? old('creado_por', $reserva->creado_por) : $reserva->creado_por;
                $estadoOptions = \App\Models\Reserva::ESTADOS;
            @endphp
            <div id="reserva-edit-modal-{{ $reserva->id }}"
                class="reserva-edit-modal fixed inset-0 bg-black/60 backdrop-blur-sm hidden items-center justify-center z-50 px-4 py-8"
                data-reserva-edit-modal data-open-default="{{ $reservaRowEditing ? 'true' : 'false' }}">
                <div class="relative w-full max-w-4xl border border-blue-500/30 bg-slate-950/95 p-6 md:p-8 shadow-2xl text-white max-h-[90vh] overflow-y-auto">
                    <button type="button" data-reserva-edit-modal-close
                        class="absolute top-3 right-3 bg-blue-600 hover:bg-red-500 text-white w-10 h-10 rounded-full shadow-lg flex items-center justify-center text-2xl font-bold transition">
                        ✕
                    </button>

                    <div class="space-y-6">
                        <div>
                            <p class="text-xs uppercase tracking-[0.3em] text-blue-300">Editar reserva</p>
                            <h2 class="text-2xl font-semibold">Actualizar reserva #{{ $reserva->id }}</h2>
                            <p class="text-emerald-400 text-sm">Modifica las fechas, importes o el estado cuando sea necesario.</p>
                        </div>

                        <form method="POST" action="{{ route('admin.reservas.update', $reserva) }}" class="grid gap-4 md:grid-cols-2 text-sm">
                            @csrf
                            @method('PUT')

                            @if ($reservaRowEditing && $reservaEditErrors?->any())
                                <div class="md:col-span-2 rounded-lg border border-red-500/40 bg-red-500/10 px-4 py-3 text-sm text-red-200">
                                    <p class="font-semibold text-red-100">No pudimos actualizar la reserva</p>
                                    <p>{{ $reservaEditErrors->first() }}</p>
                                </div>
                            @endif

                            <div>
                                <label class="block text-xs uppercase tracking-widest text-emerald-400 mb-1">Cancha</label>
                                <select name="cancha_id"
                                    class="w-full rounded-lg border bg-slate-900/70 px-3 py-2 focus:outline-none {{ $reservaRowEditing && $reservaEditErrors?->has('cancha_id') ? 'border-red-500 focus:ring focus:ring-red-500' : 'border-slate-600 focus:ring focus:ring-emerald-500' }}"
                                    required>
                                    <option value="">Selecciona una cancha</option>
                                    @foreach ($canchas as $canchaOption)
                                        <option value="{{ $canchaOption->id }}" {{ $editCanchaId === $canchaOption->id ? 'selected' : '' }}>
                                            {{ $canchaOption->nombre }}
                                        </option>
                                    @endforeach
                                </select>
                                @if ($reservaRowEditing && $reservaEditErrors?->has('cancha_id'))
                                    <p class="text-xs text-red-300 mt-1">{{ $reservaEditErrors->first('cancha_id') }}</p>
                                @endif
                            </div>

                            <div>
                                <label class="block text-xs uppercase tracking-widest text-emerald-400 mb-1">Cliente</label>
                                <select name="cliente_id"
                                    class="w-full rounded-lg border bg-slate-900/70 px-3 py-2 focus:outline-none {{ $reservaRowEditing && $reservaEditErrors?->has('cliente_id') ? 'border-red-500 focus:ring focus:ring-red-500' : 'border-slate-600 focus:ring focus:ring-emerald-500' }}"
                                    required>
                                    <option value="">Selecciona un cliente</option>
                                    @foreach ($clientes as $clienteOption)
                                        <option value="{{ $clienteOption->id }}" {{ $editClienteId === $clienteOption->id ? 'selected' : '' }}>
                                            {{ $clienteOption->nombre }}
                                        </option>
                                    @endforeach
                                </select>
                                @if ($reservaRowEditing && $reservaEditErrors?->has('cliente_id'))
                                    <p class="text-xs text-red-300 mt-1">{{ $reservaEditErrors->first('cliente_id') }}</p>
                                @endif
                            </div>

                            <div>
                                <label class="block text-xs uppercase tracking-widest text-emerald-400 mb-1">Fecha de reserva</label>
                                <input type="date" name="fecha_reserva" value="{{ $editFechaReserva }}"
                                    class="w-full rounded-lg border bg-slate-900/70 px-3 py-2 focus:outline-none {{ $reservaRowEditing && $reservaEditErrors?->has('fecha_reserva') ? 'border-red-500 focus:ring focus:ring-red-500' : 'border-slate-600 focus:ring focus:ring-emerald-500' }}"
                                    required>
                                @if ($reservaRowEditing && $reservaEditErrors?->has('fecha_reserva'))
                                    <p class="text-xs text-red-300 mt-1">{{ $reservaEditErrors->first('fecha_reserva') }}</p>
                                @endif
                            </div>

                            <div>
                                <label class="block text-xs uppercase tracking-widest text-emerald-400 mb-1">Duración (minutos)</label>
                                <input type="number" min="15" max="1440" step="15" name="duracion_minutos" value="{{ $editDuracion }}"
                                    class="w-full rounded-lg border bg-slate-900/70 px-3 py-2 focus:outline-none {{ $reservaRowEditing && $reservaEditErrors?->has('duracion_minutos') ? 'border-red-500 focus:ring focus:ring-red-500' : 'border-slate-600 focus:ring focus:ring-emerald-500' }}"
                                    required>
                                @if ($reservaRowEditing && $reservaEditErrors?->has('duracion_minutos'))
                                    <p class="text-xs text-red-300 mt-1">{{ $reservaEditErrors->first('duracion_minutos') }}</p>
                                @endif
                            </div>

                            <div>
                                <label class="block text-xs uppercase tracking-widest text-emerald-400 mb-1">Inicio</label>
                                <input type="datetime-local" name="fecha_inicio" value="{{ $editFechaInicio }}"
                                    class="w-full rounded-lg border bg-slate-900/70 px-3 py-2 focus:outline-none {{ $reservaRowEditing && $reservaEditErrors?->has('fecha_inicio') ? 'border-red-500 focus:ring focus:ring-red-500' : 'border-slate-600 focus:ring focus:ring-emerald-500' }}"
                                    required>
                                @if ($reservaRowEditing && $reservaEditErrors?->has('fecha_inicio'))
                                    <p class="text-xs text-red-300 mt-1">{{ $reservaEditErrors->first('fecha_inicio') }}</p>
                                @endif
                            </div>

                            <div>
                                <label class="block text-xs uppercase tracking-widest text-emerald-400 mb-1">Fin</label>
                                <input type="datetime-local" name="fecha_fin" value="{{ $editFechaFin }}"
                                    class="w-full rounded-lg border bg-slate-900/70 px-3 py-2 focus:outline-none {{ $reservaRowEditing && $reservaEditErrors?->has('fecha_fin') ? 'border-red-500 focus:ring focus:ring-red-500' : 'border-slate-600 focus:ring focus:ring-emerald-500' }}"
                                    required>
                                @if ($reservaRowEditing && $reservaEditErrors?->has('fecha_fin'))
                                    <p class="text-xs text-red-300 mt-1">{{ $reservaEditErrors->first('fecha_fin') }}</p>
                                @endif
                            </div>

                            <div>
                                <label class="block text-xs uppercase tracking-widest text-emerald-400 mb-1">Precio por hora</label>
                                <input type="number" min="0" step="0.01" name="precio_hora" value="{{ $editPrecioHora }}"
                                    class="w-full rounded-lg border bg-slate-900/70 px-3 py-2 focus:outline-none {{ $reservaRowEditing && $reservaEditErrors?->has('precio_hora') ? 'border-red-500 focus:ring focus:ring-red-500' : 'border-slate-600 focus:ring focus:ring-emerald-500' }}"
                                    required>
                                @if ($reservaRowEditing && $reservaEditErrors?->has('precio_hora'))
                                    <p class="text-xs text-red-300 mt-1">{{ $reservaEditErrors->first('precio_hora') }}</p>
                                @endif
                            </div>

                            <div>
                                <label class="block text-xs uppercase tracking-widest text-emerald-400 mb-1">Total</label>
                                <input type="number" min="0" step="0.01" name="total" value="{{ $editTotal }}"
                                    class="w-full rounded-lg border bg-slate-900/70 px-3 py-2 focus:outline-none {{ $reservaRowEditing && $reservaEditErrors?->has('total') ? 'border-red-500 focus:ring focus:ring-red-500' : 'border-slate-600 focus:ring focus:ring-emerald-500' }}"
                                    required>
                                @if ($reservaRowEditing && $reservaEditErrors?->has('total'))
                                    <p class="text-xs text-red-300 mt-1">{{ $reservaEditErrors->first('total') }}</p>
                                @endif
                            </div>

                            <div>
                                <label class="block text-xs uppercase tracking-widest text-emerald-400 mb-1">Estado</label>
                                <select name="estado"
                                    class="w-full rounded-lg border bg-slate-900/70 px-3 py-2 focus:outline-none {{ $reservaRowEditing && $reservaEditErrors?->has('estado') ? 'border-red-500 focus:ring focus:ring-red-500' : 'border-slate-600 focus:ring focus:ring-emerald-500' }}"
                                    required>
                                    <option value="">Selecciona un estado</option>
                                    @foreach ($estadoOptions as $estado)
                                        <option value="{{ $estado }}" {{ $editEstado === $estado ? 'selected' : '' }}>
                                            {{ ucfirst($estado) }}
                                        </option>
                                    @endforeach
                                </select>
                                @if ($reservaRowEditing && $reservaEditErrors?->has('estado'))
                                    <p class="text-xs text-red-300 mt-1">{{ $reservaEditErrors->first('estado') }}</p>
                                @endif
                            </div>

                            <div class="md:col-span-2">
                                <label class="block text-xs uppercase tracking-widest text-emerald-400 mb-1">Observaciones</label>
                                <textarea name="observaciones" rows="3"
                                    class="w-full rounded-lg border bg-slate-900/70 px-3 py-2 focus:outline-none {{ $reservaRowEditing && $reservaEditErrors?->has('observaciones') ? 'border-red-500 focus:ring focus:ring-red-500' : 'border-slate-600 focus:ring focus:ring-emerald-500' }}"
                                    placeholder="Notas internas o mensajes para el equipo">{{ $editObservaciones }}</textarea>
                                @if ($reservaRowEditing && $reservaEditErrors?->has('observaciones'))
                                    <p class="text-xs text-red-300 mt-1">{{ $reservaEditErrors->first('observaciones') }}</p>
                                @endif
                            </div>

                            <input type="hidden" name="creado_por" value="{{ $editCreadoPor }}">
                            @if ($reservaRowEditing && $reservaEditErrors?->has('creado_por'))
                                <div class="md:col-span-2 text-xs text-red-300">
                                    {{ $reservaEditErrors->first('creado_por') }}
                                </div>
                            @endif

                            <div class="md:col-span-2 flex justify-end gap-3">
                                <button type="button" data-reserva-edit-modal-close
                                    class="px-4 py-2 rounded-lg border border-slate-600 text-slate-300 hover:bg-slate-800 transition">
                                    Cancelar
                                </button>
                                <button type="submit"
                                    class="px-5 py-2 rounded-lg bg-blue-600 hover:bg-blue-700 text-white font-semibold transition">
                                    Guardar cambios
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        @endforeach
        <!-- MODAL ELIMINAR RESERVA -->
        <div id="reserva-delete-modal"
            class="fixed inset-0 bg-black/70 backdrop-blur-sm hidden items-center justify-center z-50 px-4 py-8">
            <div class="relative w-full max-w-lg border border-rose-500/40 bg-slate-950/95 p-6 shadow-2xl text-white">
                <button type="button" data-reserva-delete-cancel
                    class="absolute top-3 right-3 text-emerald-400 hover:text-white transition" aria-label="Cerrar">
                    ✕
                </button>
                <div class="space-y-4">
                    <div class="flex items-start gap-3">
                        <div class="rounded-full p-2 bg-rose-500/15 text-rose-300">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" stroke="currentColor"
                                stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M12 9v4" />
                                <path d="M12 17h.01" />
                                <path d="M10 3h4l7 12-2 4H5l-2-4 7-12z" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm uppercase tracking-[0.3em] text-rose-300">Confirmar acción</p>
                            <p class="text-lg font-semibold text-white">
                                ¿Eliminar esta reserva?
                            </p>
                            <p class="text-sm text-emerald-400 mt-1">
                                Esta acción no se puede deshacer y liberará el horario seleccionado.
                            </p>
                        </div>
                    </div>

                    <div class="rounded-lg border border-slate-800 bg-slate-900/60 p-4">
                        <p class="text-sm text-emerald-400">Cliente</p>
                        <p class="text-lg font-semibold text-white" data-reserva-delete-name>—</p>
                        <p class="text-xs text-emerald-500" data-reserva-delete-id></p>
                    </div>

                    <div class="flex justify-end gap-3">
                        <button type="button" data-reserva-delete-cancel
                            class="px-4 py-2 rounded-lg border border-slate-600 text-slate-300 hover:bg-slate-800 transition">
                            Cancelar
                        </button>
                        <button type="button" data-reserva-delete-confirm
                            class="px-5 py-2 rounded-lg bg-rose-500 hover:bg-rose-600 text-white font-semibold transition">
                            Eliminar
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- SECCIÓN BLOQUEOS -->
    <section id="bloqueos" data-section="bloqueos" class="scroll-mt-32 hidden">
        <div class="bg-gradient-to-br from-emerald-950 via-emerald-900 to-emerald-800 min-h-screen flex justify-center p-4 sm:p-6 md:p-10">
            <div class="w-full max-w-5xl space-y-4">
                <div class="flex flex-col gap-3 sm:gap-2 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <h1 class="text-lg sm:text-xl font-bold text-white">Bloqueos programados</h1>
                        <p class="text-xs sm:text-sm text-gray-600">Controla los horarios donde las canchas no estarán disponibles.</p>
                    </div>
                    <button id="abrirBloqueoModal" type="button"
                        class="flex items-center justify-center gap-2 bg-green-600 hover:bg-green-700 text-slate-950 font-semibold px-4 py-2 rounded-lg shadow-md transition whitespace-nowrap">
                        <span class="text-xl font-bold">+</span>
                        <span class="hidden xs:inline">NUEVO BLOQUEO</span>
                        <span class="xs:hidden">NUEVO</span>
                    </button>
                </div>

                <div class="overflow-x-auto rounded-2xl border border-emerald-800/60 bg-emerald-950/70 shadow-2xl">
                <div class="bg-emerald-950/60 border border-emerald-800 rounded-2xl p-4 shadow-2xl">
                    <div class="grid gap-4 sm:grid-cols-2">
                        <div>
                            <label for="bloqueo-cancha-search"
                                class="text-xs uppercase tracking-[0.3em] text-emerald-400">Buscar por cancha</label>
                            <div class="relative mt-1">
                                <span class="absolute inset-y-0 left-3 flex items-center text-emerald-500">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                                        stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="m21 21-4.35-4.35m0 0A7 7 0 1 0 6.5 6.5a7 7 0 0 0 10.15 10.15z" />
                                    </svg>
                                </span>
                                <input id="bloqueo-cancha-search" type="search" placeholder="Ej. Arena Indoor"
                                    class="w-full rounded-xl border border-emerald-700 bg-emerald-900/70 pl-9 pr-3 py-2 text-sm text-white placeholder-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500"
                                    data-bloqueo-search>
                            </div>
                        </div>

                        <div>
                            <label for="bloqueo-type-filter"
                                class="text-xs uppercase tracking-[0.3em] text-emerald-400">Filtrar por tipo</label>
                            <select id="bloqueo-type-filter"
                                class="mt-1 w-full rounded-xl border border-emerald-700 bg-emerald-900/70 px-3 py-2 text-sm text-white focus:outline-none focus:ring-2 focus:ring-emerald-500"
                                data-bloqueo-type>
                                <option value="">Todos los tipos</option>
                                @foreach ($bloqueoTipos as $tipo)
                                    <option value="{{ $tipo }}">{{ $tipo }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <div class="overflow-x-auto rounded-2xl border border-slate-800 bg-slate-950/60 shadow-2xl">
                    <table class="w-full text-left text-sm text-gray-300">
                        <thead class="bg-emerald-950/60">
                            <tr class="uppercase text-xs text-emerald-400 tracking-wide">
                                <th class="px-6 py-3">Cancha</th>
                                <th class="px-6 py-3">Inicio</th>
                                <th class="px-6 py-3">Fin</th>
                                <th class="px-6 py-3">Motivo</th>
                                <th class="px-6 py-3">Registró</th>
                                <th class="px-6 py-3 text-right">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-emerald-900/50">
                            @forelse ($bloqueos as $bloqueo)
                                <tr class="hover:bg-emerald-900/40 transition-colors"
                                    data-bloqueo-row
                                    data-bloqueo-cancha="{{ mb_strtolower(optional($bloqueo->cancha)->nombre ?? '') }}"
                                    data-bloqueo-tipo="{{ mb_strtolower(optional($bloqueo->cancha)->tipo ?? '') }}">
                                    <td class="px-6 py-4">
                                        <div class="font-semibold text-white">{{ optional($bloqueo->cancha)->nombre ?? 'Cancha eliminada' }}</div>
                                        <div class="text-xs text-emerald-500">ID #{{ $bloqueo->cancha_id }}</div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="font-semibold text-emerald-400">
                                            {{ optional($bloqueo->fecha_inicio)->format('d/m/Y H:i') ?? '—' }}
                                        </div>
                                        <p class="text-xs text-emerald-400">Hora local</p>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="font-semibold text-rose-300">
                                            {{ optional($bloqueo->fecha_fin)->format('d/m/Y H:i') ?? '—' }}
                                        </div>
                                        <p class="text-xs text-emerald-400">Hora local</p>
                                    </td>
                                    <td class="px-6 py-4 max-w-xs">
                                        <p class="text-sm text-slate-300 leading-snug">
                                            {{ $bloqueo->motivo }}
                                        </p>
                                    </td>
                                    <td class="px-6 py-4">
                                        <p class="font-semibold text-white">
                                            {{ optional($bloqueo->creador)->name ?? 'Usuario no disponible' }}
                                        </p>
                                        <p class="text-xs text-emerald-400">
                                            {{ optional($bloqueo->creador)->email ?? 'Sin correo' }}
                                        </p>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center justify-end gap-3 text-lg">
                                            <button type="button"
                                                class="hover:text-emerald-300 transition-colors"
                                                data-bloqueo-edit-target="bloqueo-edit-modal-{{ $bloqueo->id }}"
                                                title="Editar bloqueo">
                                                <svg width="20" height="20" fill="currentColor" viewBox="0 0 24 24">
                                                    <path d="M3 17.25V21h3.75L17.81 9.94l-3.75-3.75L3 17.25zM21.41 6.34c.39-.39.39-1.02 0-1.41l-2.34-2.34a1 1 0 0 0-1.41 0l-1.83 1.83 3.75 3.75 1.83-1.83z" />
                                                </svg>
                                            </button>
                                            <form id="delete-bloqueo-form-{{ $bloqueo->id }}" method="POST"
                                                action="{{ route('admin.bloqueos.destroy', $bloqueo) }}">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button" class="hover:text-red-400 transition-colors"
                                                    data-bloqueo-delete-target="delete-bloqueo-form-{{ $bloqueo->id }}"
                                                    title="Eliminar bloqueo">
                                                    <svg width="20" height="20" fill="currentColor" viewBox="0 0 24 24">
                                                        <path d="M6 19a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2V7H6v12zm3-9h2v7H9V10zm4 0h2v7h-2v-7z" />
                                                        <path d="M15.5 4l-1-1h-5l-1 1H5v2h14V4z" />
                                                    </svg>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-10 text-center text-emerald-400">
                                        Aún no hay bloqueos registrados.
                                    </td>
                                </tr>
                            @endforelse
                            @if ($bloqueos->isNotEmpty())
                                <tr class="hidden" data-bloqueo-empty-row>
                                    <td colspan="6" class="px-6 py-10 text-center text-emerald-400">
                                        No se encontraron bloqueos que coincidan con los filtros aplicados.
                                    </td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- MODAL PARA CREAR BLOQUEO -->
        <div id="bloqueo-modal"
            class="fixed inset-0 bg-black/60 backdrop-blur-sm hidden items-center justify-center z-50 px-4 py-4 sm:py-8">
            <div
                class="relative w-full max-w-3xl border border-slate-800 bg-slate-950/95 p-4 sm:p-6 md:p-8 shadow-2xl max-h-[90vh] overflow-y-auto text-white">
                <button type="button" data-bloqueo-modal-close
                    class="absolute top-2 right-2 sm:top-4 sm:right-4 bg-emerald-500 text-slate-950 w-8 h-8 sm:w-10 sm:h-10 rounded-full shadow-lg flex items-center justify-center text-xl sm:text-2xl font-bold hover:bg-red-500 hover:text-white transition">
                    ✕
                </button>

                <div class="space-y-4 sm:space-y-6">
                    <div>
                        <p class="text-xs sm:text-sm uppercase tracking-[0.2em] sm:tracking-[0.3em] text-emerald-300">Nuevo bloqueo</p>
                        <h2 class="text-xl sm:text-2xl font-semibold">Bloquear una cancha</h2>
                        <p class="text-emerald-400 text-xs sm:text-sm">Define el intervalo de fechas y el motivo del bloqueo.</p>
                    </div>

                    <form method="POST" action="{{ route('admin.bloqueos.store') }}" class="grid gap-4 md:grid-cols-2 text-sm">
                        @csrf
                        <div class="md:col-span-2">
                            <label class="block text-xs uppercase tracking-widest text-emerald-400 mb-1">Cancha</label>
                            @php
                                $crearCanchaError = $crearBloqueoErrors?->has('cancha_id');
                            @endphp
                            <select name="cancha_id"
                                class="w-full rounded-lg border bg-slate-900/70 px-3 py-2 focus:outline-none {{ $crearCanchaError ? 'border-red-500 focus:ring focus:ring-red-500' : 'border-slate-600 focus:ring focus:ring-emerald-500' }}"
                                required>
                                <option value="">Selecciona una cancha</option>
                                @foreach ($canchas as $canchaOption)
                                    <option value="{{ $canchaOption->id }}" {{ (int) old('cancha_id') === $canchaOption->id ? 'selected' : '' }}>
                                        {{ $canchaOption->nombre }}
                                    </option>
                                @endforeach
                            </select>
                            @error('cancha_id', 'crearBloqueo')
                                <p class="text-xs text-red-300 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-xs uppercase tracking-widest text-emerald-400 mb-1">Fecha y hora de inicio</label>
                            @php
                                $crearInicioError = $crearBloqueoErrors?->has('fecha_inicio');
                            @endphp
                            <input type="datetime-local" name="fecha_inicio" value="{{ old('fecha_inicio') }}"
                                class="w-full rounded-lg border bg-slate-900/70 px-3 py-2 focus:outline-none {{ $crearInicioError ? 'border-red-500 focus:ring focus:ring-red-500' : 'border-slate-600 focus:ring focus:ring-emerald-500' }}"
                                required>
                            @error('fecha_inicio', 'crearBloqueo')
                                <p class="text-xs text-red-300 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-xs uppercase tracking-widest text-emerald-400 mb-1">Fecha y hora de fin</label>
                            @php
                                $crearFinError = $crearBloqueoErrors?->has('fecha_fin');
                            @endphp
                            <input type="datetime-local" name="fecha_fin" value="{{ old('fecha_fin') }}"
                                class="w-full rounded-lg border bg-slate-900/70 px-3 py-2 focus:outline-none {{ $crearFinError ? 'border-red-500 focus:ring focus:ring-red-500' : 'border-slate-600 focus:ring focus:ring-emerald-500' }}"
                                required>
                            @error('fecha_fin', 'crearBloqueo')
                                <p class="text-xs text-red-300 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-xs uppercase tracking-widest text-emerald-400 mb-1">Motivo</label>
                            @php
                                $crearMotivoError = $crearBloqueoErrors?->has('motivo');
                            @endphp
                            <textarea name="motivo" rows="3"
                                class="w-full rounded-lg border bg-slate-900/70 px-3 py-2 focus:outline-none {{ $crearMotivoError ? 'border-red-500 focus:ring focus:ring-red-500' : 'border-slate-600 focus:ring focus:ring-emerald-500' }}"
                                required>{{ old('motivo') }}</textarea>
                            @error('motivo', 'crearBloqueo')
                                <p class="text-xs text-red-300 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="md:col-span-2 flex justify-end gap-3">
                            <button type="button" data-bloqueo-modal-close
                                class="px-4 py-2 rounded-lg border border-slate-600 text-slate-300 hover:bg-slate-800 transition">
                                Cancelar
                            </button>
                            <button type="submit"
                                class="px-5 py-2 rounded-lg bg-green-600 hover:bg-green-700 text-slate-950 font-semibold transition">
                                Guardar bloqueo
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- MODALES PARA EDITAR BLOQUEOS -->
        @foreach ($bloqueos as $bloqueo)
            @php
                $rowIsEditingBloqueo = (int) $editarBloqueoId === $bloqueo->id;
                $editCanchaId = $rowIsEditingBloqueo ? (int) old('cancha_id', $bloqueo->cancha_id) : $bloqueo->cancha_id;
                $editFechaInicio = $rowIsEditingBloqueo ? old('fecha_inicio', optional($bloqueo->fecha_inicio)->format('Y-m-d\\TH:i')) : optional($bloqueo->fecha_inicio)->format('Y-m-d\\TH:i');
                $editFechaFin = $rowIsEditingBloqueo ? old('fecha_fin', optional($bloqueo->fecha_fin)->format('Y-m-d\\TH:i')) : optional($bloqueo->fecha_fin)->format('Y-m-d\\TH:i');
                $editMotivo = $rowIsEditingBloqueo ? old('motivo', $bloqueo->motivo) : $bloqueo->motivo;
            @endphp
            <div id="bloqueo-edit-modal-{{ $bloqueo->id }}"
                class="bloqueo-edit-modal fixed inset-0 bg-black/60 backdrop-blur-sm hidden items-center justify-center z-50 px-4 py-4 sm:py-8"
                data-bloqueo-edit-modal data-open-default="{{ $rowIsEditingBloqueo ? 'true' : 'false' }}">
                <div
                    class="relative w-full max-w-3xl border border-slate-800 bg-slate-950/95 p-4 sm:p-6 md:p-8 shadow-2xl max-h-[90vh] overflow-y-auto text-white">
                    <button type="button" data-bloqueo-edit-modal-close
                        class="absolute top-2 right-2 sm:top-4 sm:right-4 bg-emerald-500 text-slate-950 w-8 h-8 sm:w-10 sm:h-10 rounded-full shadow-lg flex items-center justify-center text-xl sm:text-2xl font-bold hover:bg-red-500 hover:text-white transition">
                        ✕
                    </button>

                    <div class="space-y-4 sm:space-y-6">
                        <div>
                            <p class="text-xs sm:text-sm uppercase tracking-[0.2em] sm:tracking-[0.3em] text-emerald-300">Editar bloqueo</p>
                            <h2 class="text-xl sm:text-2xl font-semibold">Actualizar bloqueo de {{ optional($bloqueo->cancha)->nombre ?? 'Cancha eliminada' }}</h2>
                            <p class="text-emerald-400 text-xs sm:text-sm">Ajusta fechas o cambia el motivo cuando sea necesario.</p>
                        </div>

                        <form method="POST" action="{{ route('admin.bloqueos.update', $bloqueo) }}" class="grid gap-4 md:grid-cols-2 text-sm">
                            @csrf
                            @method('PUT')

                            <div class="md:col-span-2">
                                <label class="block text-xs uppercase tracking-widest text-emerald-400 mb-1">Cancha</label>
                                @php
                                    $editCanchaError = $rowIsEditingBloqueo && $editarBloqueoErrors?->has('cancha_id');
                                @endphp
                                <select name="cancha_id"
                                    class="w-full rounded-lg border bg-slate-900/70 px-3 py-2 focus:outline-none {{ $editCanchaError ? 'border-red-500 focus:ring focus:ring-red-500' : 'border-slate-600 focus:ring focus:ring-emerald-500' }}"
                                    required>
                                    <option value="">Selecciona una cancha</option>
                                    @foreach ($canchas as $canchaOption)
                                        <option value="{{ $canchaOption->id }}" {{ $editCanchaId === $canchaOption->id ? 'selected' : '' }}>
                                            {{ $canchaOption->nombre }}
                                        </option>
                                    @endforeach
                                </select>
                                @if ($rowIsEditingBloqueo && $errors->editarBloqueo->has('cancha_id'))
                                    <p class="text-xs text-red-300 mt-1">{{ $errors->editarBloqueo->first('cancha_id') }}</p>
                                @endif
                            </div>

                            <div>
                                <label class="block text-xs uppercase tracking-widest text-emerald-400 mb-1">Fecha y hora de inicio</label>
                                @php
                                    $editInicioError = $rowIsEditingBloqueo && $editarBloqueoErrors?->has('fecha_inicio');
                                @endphp
                                <input type="datetime-local" name="fecha_inicio" value="{{ $editFechaInicio }}"
                                    class="w-full rounded-lg border bg-slate-900/70 px-3 py-2 focus:outline-none {{ $editInicioError ? 'border-red-500 focus:ring focus:ring-red-500' : 'border-slate-600 focus:ring focus:ring-emerald-500' }}"
                                    required>
                                @if ($rowIsEditingBloqueo && $errors->editarBloqueo->has('fecha_inicio'))
                                    <p class="text-xs text-red-300 mt-1">{{ $errors->editarBloqueo->first('fecha_inicio') }}</p>
                                @endif
                            </div>

                            <div>
                                <label class="block text-xs uppercase tracking-widest text-emerald-400 mb-1">Fecha y hora de fin</label>
                                @php
                                    $editFinError = $rowIsEditingBloqueo && $editarBloqueoErrors?->has('fecha_fin');
                                @endphp
                                <input type="datetime-local" name="fecha_fin" value="{{ $editFechaFin }}"
                                    class="w-full rounded-lg border bg-slate-900/70 px-3 py-2 focus:outline-none {{ $editFinError ? 'border-red-500 focus:ring focus:ring-red-500' : 'border-slate-600 focus:ring focus:ring-emerald-500' }}"
                                    required>
                                @if ($rowIsEditingBloqueo && $errors->editarBloqueo->has('fecha_fin'))
                                    <p class="text-xs text-red-300 mt-1">{{ $errors->editarBloqueo->first('fecha_fin') }}</p>
                                @endif
                            </div>

                            <div class="md:col-span-2">
                                <label class="block text-xs uppercase tracking-widest text-emerald-400 mb-1">Motivo</label>
                                @php
                                    $editMotivoError = $rowIsEditingBloqueo && $editarBloqueoErrors?->has('motivo');
                                @endphp
                                <textarea name="motivo" rows="3"
                                    class="w-full rounded-lg border bg-slate-900/70 px-3 py-2 focus:outline-none {{ $editMotivoError ? 'border-red-500 focus:ring focus:ring-red-500' : 'border-slate-600 focus:ring focus:ring-emerald-500' }}"
                                    required>{{ $editMotivo }}</textarea>
                                @if ($rowIsEditingBloqueo && $errors->editarBloqueo->has('motivo'))
                                    <p class="text-xs text-red-300 mt-1">{{ $errors->editarBloqueo->first('motivo') }}</p>
                                @endif
                            </div>

                            <div class="md:col-span-2 flex justify-end gap-3">
                                <button type="button" data-bloqueo-edit-modal-close
                                    class="px-4 py-2 rounded-lg border border-slate-600 text-slate-300 hover:bg-slate-800 transition">
                                    Cancelar
                                </button>
                                <button type="submit"
                                    class="px-5 py-2 rounded-lg bg-green-600 hover:bg-green-700 text-slate-950 font-semibold transition">
                                    Guardar cambios
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        @endforeach

        <!-- MODAL ELIMINAR BLOQUEO -->
        <div id="bloqueo-delete-modal"
            class="fixed inset-0 bg-black/70 backdrop-blur-sm hidden items-center justify-center z-50 px-4 py-8">
            <div class="relative w-full max-w-lg border border-red-500/40 bg-slate-950/95 p-6 shadow-2xl text-white">
                <button type="button" data-bloqueo-delete-cancel
                    class="absolute top-3 right-3 text-emerald-400 hover:text-white transition" aria-label="Cerrar">
                    ✕
                </button>
                <div class="space-y-4">
                    <div class="flex items-start gap-3">
                        <div class="rounded-full p-2 bg-red-500/15 text-red-300">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" stroke="currentColor"
                                stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M12 9v4" />
                                <path d="M12 17h.01" />
                                <path d="M10 3h4l7 12-2 4H5l-2-4 7-12z" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm uppercase tracking-[0.3em] text-red-300">Alerta</p>
                            <p class="text-lg font-semibold text-white">
                                El bloqueo seleccionado se eliminará de forma permanente.
                            </p>
                            <p class="text-sm text-emerald-400 mt-1">Esta acción no se puede deshacer.</p>
                        </div>
                    </div>

                    <div class="flex justify-end gap-3">
                        <button type="button" data-bloqueo-delete-cancel
                            class="px-4 py-2 rounded-lg border border-slate-600 text-slate-300 hover:bg-slate-800 transition">
                            Cancelar
                        </button>
                        <button type="button" data-bloqueo-delete-confirm
                            class="px-5 py-2 rounded-lg bg-red-500 hover:bg-red-600 text-white font-semibold transition">
                            Eliminar
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- SECCIÓN PRECIOS -->
    <section id="precios" data-section="precios" class="scroll-mt-32 hidden">
        <div class="bg-gradient-to-br from-emerald-950 via-emerald-900 to-emerald-800 min-h-screen flex justify-center p-4 sm:p-6 md:p-10">
            <div class="w-full max-w-5xl space-y-4">
                <div class="flex flex-col gap-3 sm:gap-2 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <h1 class="text-lg sm:text-xl font-bold text-white">Historial de precios por cancha</h1>
                        <p class="text-xs sm:text-sm text-gray-600">Registra, edita o finaliza periodos de vigencia para cada cancha.</p>
                    </div>
                    <button id="abrirPrecioModal" type="button"
                        class="flex items-center justify-center gap-2 bg-green-600 hover:bg-green-700 text-slate-950 font-semibold px-4 py-2 rounded-lg shadow-md transition whitespace-nowrap">
                        <span class="text-xl font-bold">+</span>
                        <span class="hidden xs:inline">NUEVO PRECIO</span>
                        <span class="xs:hidden">NUEVO</span>
                    </button>
                </div>

                <div class="overflow-x-auto rounded-2xl border border-emerald-800/60 bg-emerald-950/70 shadow-2xl">
                <div class="bg-emerald-950/60 border border-emerald-800 rounded-2xl p-4 shadow-2xl">
                    <div class="grid gap-4 md:grid-cols-2">
                        <div>
                            <label for="precio-cancha-search"
                                class="text-xs uppercase tracking-[0.3em] text-emerald-400">Buscar por cancha</label>
                            <div class="relative mt-1">
                                <span class="absolute inset-y-0 left-3 flex items-center text-emerald-500">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                                        stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="m21 21-4.35-4.35m0 0A7 7 0 1 0 6.5 6.5a7 7 0 0 0 10.15 10.15z" />
                                    </svg>
                                </span>
                                <input id="precio-cancha-search" type="search" placeholder="Ej. Cancha Central"
                                    class="w-full rounded-xl border border-emerald-700 bg-emerald-900/70 pl-9 pr-3 py-2 text-sm text-white placeholder-emerald-500 focus:outline-none focus:ring-2 focus:ring-amber-500"
                                    data-precio-search>
                            </div>
                        </div>

                        <div>
                            <label for="precio-type-filter"
                                class="text-xs uppercase tracking-[0.3em] text-emerald-400">Filtrar por tipo</label>
                            <select id="precio-type-filter"
                                class="mt-1 w-full rounded-xl border border-emerald-700 bg-emerald-900/70 px-3 py-2 text-sm text-white focus:outline-none focus:ring-2 focus:ring-amber-500"
                                data-precio-type>
                                <option value="">Todos los tipos</option>
                                @foreach ($precioTipos as $tipo)
                                    <option value="{{ $tipo }}">{{ $tipo }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <div class="overflow-x-auto rounded-2xl border border-slate-800 bg-slate-950/60 shadow-2xl">
                    <table class="w-full text-left text-sm text-gray-300">
                        <thead class="bg-emerald-950/60">
                            <tr class="uppercase text-xs text-emerald-400 tracking-wide">
                                <th class="px-6 py-3">Cancha</th>
                                <th class="px-6 py-3 text-right">Precio/Hora</th>
                                <th class="px-6 py-3">Desde</th>
                                <th class="px-6 py-3">Hasta</th>
                                <th class="px-6 py-3 text-center">Estado</th>
                                <th class="px-6 py-3 text-right">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-emerald-900/50">
                            @forelse ($precios as $precio)
                                @php
                                    $rowIsEditingPrecio = (int) $editarPrecioId === $precio->id;
                                    $editCanchaId = $rowIsEditingPrecio ? (int) old('cancha_id', $precio->cancha_id) : $precio->cancha_id;
                                    $editPrecioHora = $rowIsEditingPrecio ? old('precio_hora', $precio->precio_hora) : $precio->precio_hora;
                                    $editFechaDesde = $rowIsEditingPrecio ? old('fecha_desde', optional($precio->fecha_desde)->format('Y-m-d\\TH:i')) : optional($precio->fecha_desde)->format('Y-m-d\\TH:i');
                                    $editFechaHasta = $rowIsEditingPrecio ? old('fecha_hasta', optional($precio->fecha_hasta)->format('Y-m-d\\TH:i')) : optional($precio->fecha_hasta)->format('Y-m-d\\TH:i');
                                    $editFechaDesde = $editFechaDesde ?? '';
                                    $editFechaHasta = $editFechaHasta ?? '';
                                    $fechaDesde = $precio->fecha_desde;
                                    $fechaHasta = $precio->fecha_hasta;
                                    $state = [
                                        'label' => 'Vigente',
                                        'classes' => 'bg-emerald-500/15 text-emerald-300',
                                        'helper' => 'Disponible para reservas',
                                    ];
                                    $now = now();
                                    if ($fechaDesde && $fechaDesde->isFuture()) {
                                        $state = [
                                            'label' => 'Programado',
                                            'classes' => 'bg-amber-500/10 text-amber-300',
                                            'helper' => 'Inicia el ' . $fechaDesde->format('d/m/Y H:i'),
                                        ];
                                    } elseif ($fechaHasta && $fechaHasta->lessThanOrEqualTo($now)) {
                                        $state = [
                                            'label' => 'Finalizado',
                                            'classes' => 'bg-red-500/10 text-red-300',
                                            'helper' => 'Terminó el ' . $fechaHasta->format('d/m/Y H:i'),
                                        ];
                                    }
                                @endphp
                                <tr class="hover:bg-emerald-900/40 transition-colors"
                                    data-precio-row
                                    data-precio-cancha="{{ mb_strtolower(optional($precio->cancha)->nombre ?? '') }}"
                                    data-precio-tipo="{{ mb_strtolower(optional($precio->cancha)->tipo ?? '') }}">
                                    <td class="px-6 py-4">
                                        <div class="font-semibold text-white">{{ optional($precio->cancha)->nombre ?? 'Cancha no disponible' }}</div>
                                        <div class="text-xs text-emerald-500">ID #{{ $precio->cancha_id }}</div>
                                    </td>
                                    <td class="px-6 py-4 text-right font-semibold text-amber-300">
                                        ${{ number_format((float) $precio->precio_hora, 2, '.', ',') }}
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="font-semibold text-slate-200">
                                            {{ optional($fechaDesde)->format('d/m/Y H:i') ?? '—' }}
                                        </div>
                                        <p class="text-xs text-emerald-400">Hora local</p>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="font-semibold text-slate-200">
                                            {{ optional($fechaHasta)->format('d/m/Y H:i') ?? 'Sin fecha de cierre' }}
                                        </div>
                                        <p class="text-xs text-emerald-400">
                                            {{ $fechaHasta ? 'Hora límite' : 'Vigencia abierta' }}
                                        </p>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <span class="inline-flex flex-col items-center rounded-full px-3 py-1 text-xs font-semibold {{ $state['classes'] }}">
                                            {{ $state['label'] }}
                                            <span class="mt-0.5 text-[10px] font-normal text-current">
                                                {{ $state['helper'] }}
                                            </span>
                                        </span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center justify-end gap-3 text-lg">
                                            <button type="button"
                                                class="hover:text-amber-300 transition-colors"
                                                data-precio-edit-target="precio-edit-modal-{{ $precio->id }}"
                                                title="Editar precio">
                                                <svg width="20" height="20" fill="currentColor" viewBox="0 0 24 24">
                                                    <path d="M3 17.25V21h3.75L17.81 9.94l-3.75-3.75L3 17.25zM21.41 6.34c.39-.39.39-1.02 0-1.41l-2.34-2.34a1 1 0 0 0-1.41 0l-1.83 1.83 3.75 3.75 1.83-1.83z" />
                                                </svg>
                                            </button>
                                            <form id="delete-precio-form-{{ $precio->id }}" method="POST"
                                                action="{{ route('admin.precios.destroy', $precio) }}">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button" class="hover:text-red-400 transition-colors"
                                                    data-precio-delete-target="delete-precio-form-{{ $precio->id }}"
                                                    title="Eliminar precio">
                                                    <svg width="20" height="20" fill="currentColor" viewBox="0 0 24 24">
                                                        <path d="M6 19a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2V7H6v12zm3-9h2v7H9V10zm4 0h2v7h-2v-7z" />
                                                        <path d="M15.5 4l-1-1h-5l-1 1H5v2h14V4z" />
                                                    </svg>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-10 text-center text-emerald-400">
                                        Todavía no hay historial de precios registrado.
                                    </td>
                                </tr>
                            @endforelse
                            @if ($precios->isNotEmpty())
                                <tr class="hidden" data-precio-empty-row>
                                    <td colspan="6" class="px-6 py-10 text-center text-emerald-400">
                                        No se encontraron precios que coincidan con los filtros aplicados.
                                    </td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- MODAL PARA CREAR PRECIO -->
        <div id="precio-modal"
            class="fixed inset-0 bg-black/60 backdrop-blur-sm hidden items-center justify-center z-50 px-4 py-8">
            <div
                class="relative w-full max-w-3xl border border-slate-800 bg-slate-950/95 p-8 shadow-2xl max-h-[90vh] overflow-y-auto text-white">
                <button type="button" data-precio-modal-close
                    class="absolute top-4 right-4 bg-amber-500 text-slate-950 w-10 h-10 rounded-full shadow-lg flex items-center justify-center text-2xl font-bold hover:bg-red-500 hover:text-white transition">
                    ✕
                </button>

                <div class="space-y-6">
                    <div>
                        <p class="text-sm uppercase tracking-[0.3em] text-amber-300">Nuevo precio</p>
                        <h2 class="text-2xl font-semibold">Definir vigencia y valor</h2>
                        <p class="text-emerald-400 text-sm">Establece la cancha, el precio y el periodo de aplicación.</p>
                    </div>

                    <form method="POST" action="{{ route('admin.precios.store') }}" class="grid gap-4 md:grid-cols-2 text-sm">
                        @csrf
                        @if ($crearPrecioErrors?->any())
                            <div class="md:col-span-2 rounded-lg border border-red-500/40 bg-red-500/10 px-4 py-3 text-sm text-red-200">
                                <p class="font-semibold text-red-100">No pudimos guardar el precio</p>
                                <p>{{ $crearPrecioErrors->first() }}</p>
                            </div>
                        @endif
                        <div class="md:col-span-2">
                            <label class="block text-xs uppercase tracking-widest text-emerald-400 mb-1">Cancha</label>
                            @php
                                $crearPrecioCanchaError = $crearPrecioErrors?->has('cancha_id');
                            @endphp
                            <select name="cancha_id"
                                class="w-full rounded-lg border bg-slate-900/70 px-3 py-2 focus:outline-none {{ $crearPrecioCanchaError ? 'border-red-500 focus:ring focus:ring-red-500' : 'border-slate-600 focus:ring focus:ring-amber-500' }}"
                                required>
                                <option value="">Selecciona una cancha</option>
                                @foreach ($canchas as $canchaOption)
                                    <option value="{{ $canchaOption->id }}" {{ (int) old('cancha_id') === $canchaOption->id ? 'selected' : '' }}>
                                        {{ $canchaOption->nombre }}
                                    </option>
                                @endforeach
                            </select>
                            @error('cancha_id', 'crearPrecio')
                                <p class="text-xs text-red-300 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-xs uppercase tracking-widest text-emerald-400 mb-1">Precio por hora</label>
                            @php
                                $crearPrecioValorError = $crearPrecioErrors?->has('precio_hora');
                            @endphp
                            <input type="number" min="0" step="0.01" name="precio_hora" value="{{ old('precio_hora') }}"
                                class="w-full rounded-lg border bg-slate-900/70 px-3 py-2 focus:outline-none {{ $crearPrecioValorError ? 'border-red-500 focus:ring focus:ring-red-500' : 'border-slate-600 focus:ring focus:ring-amber-500' }}"
                                required>
                            @error('precio_hora', 'crearPrecio')
                                <p class="text-xs text-red-300 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-xs uppercase tracking-widest text-emerald-400 mb-1">Fecha y hora de inicio</label>
                            @php
                                $crearPrecioDesdeError = $crearPrecioErrors?->has('fecha_desde');
                            @endphp
                            <input type="datetime-local" name="fecha_desde" value="{{ old('fecha_desde') }}"
                                class="w-full rounded-lg border bg-slate-900/70 px-3 py-2 focus:outline-none {{ $crearPrecioDesdeError ? 'border-red-500 focus:ring focus:ring-red-500' : 'border-slate-600 focus:ring focus:ring-amber-500' }}"
                                required>
                            @error('fecha_desde', 'crearPrecio')
                                <p class="text-xs text-red-300 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-xs uppercase tracking-widest text-emerald-400 mb-1">Fecha y hora de fin (opcional)</label>
                            @php
                                $crearPrecioHastaError = $crearPrecioErrors?->has('fecha_hasta');
                            @endphp
                            <input type="datetime-local" name="fecha_hasta" value="{{ old('fecha_hasta') }}"
                                class="w-full rounded-lg border bg-slate-900/70 px-3 py-2 focus:outline-none {{ $crearPrecioHastaError ? 'border-red-500 focus:ring focus:ring-red-500' : 'border-slate-600 focus:ring focus:ring-amber-500' }}">
                            @error('fecha_hasta', 'crearPrecio')
                                <p class="text-xs text-red-300 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="md:col-span-2 flex justify-end gap-3">
                            <button type="button" data-precio-modal-close
                                class="px-4 py-2 rounded-lg border border-slate-600 text-slate-300 hover:bg-slate-800 transition">
                                Cancelar
                            </button>
                            <button type="submit"
                                class="px-5 py-2 rounded-lg bg-green-600 hover:bg-green-700 text-slate-950 font-semibold transition">
                                Guardar precio
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- MODALES PARA EDITAR PRECIOS -->
        @foreach ($precios as $precio)
            @php
                $rowIsEditingPrecio = (int) $editarPrecioId === $precio->id;
                $editCanchaId = $rowIsEditingPrecio ? (int) old('cancha_id', $precio->cancha_id) : $precio->cancha_id;
                $editPrecioHora = $rowIsEditingPrecio ? old('precio_hora', $precio->precio_hora) : $precio->precio_hora;
                $editFechaDesde = $rowIsEditingPrecio ? old('fecha_desde', optional($precio->fecha_desde)->format('Y-m-d\\TH:i')) : optional($precio->fecha_desde)->format('Y-m-d\\TH:i');
                $editFechaHasta = $rowIsEditingPrecio ? old('fecha_hasta', optional($precio->fecha_hasta)->format('Y-m-d\\TH:i')) : optional($precio->fecha_hasta)->format('Y-m-d\\TH:i');
                $editFechaDesde = $editFechaDesde ?? '';
                $editFechaHasta = $editFechaHasta ?? '';
            @endphp
            <div id="precio-edit-modal-{{ $precio->id }}"
                class="precio-edit-modal fixed inset-0 bg-black/60 backdrop-blur-sm hidden items-center justify-center z-50 px-4 py-8"
                data-precio-edit-modal data-open-default="{{ $rowIsEditingPrecio ? 'true' : 'false' }}">
                <div
                    class="relative w-full max-w-3xl border border-slate-800 bg-slate-950/95 p-8 shadow-2xl max-h-[90vh] overflow-y-auto text-white">
                    <button type="button" data-precio-edit-modal-close
                        class="absolute top-4 right-4 bg-amber-500 text-slate-950 w-10 h-10 rounded-full shadow-lg flex items-center justify-center text-2xl font-bold hover:bg-red-500 hover:text-white transition">
                        ✕
                    </button>

                    <div class="space-y-6">
                        <div>
                            <p class="text-sm uppercase tracking-[0.3em] text-amber-300">Editar precio</p>
                            <h2 class="text-2xl font-semibold">
                                {{ optional($precio->cancha)->nombre ?? 'Cancha no disponible' }}
                            </h2>
                            <p class="text-emerald-400 text-sm">Actualiza el valor o las fechas de vigencia.</p>
                        </div>

                        <form method="POST" action="{{ route('admin.precios.update', $precio) }}" class="grid gap-4 md:grid-cols-2 text-sm">
                            @csrf
                            @method('PUT')
                            @if ($rowIsEditingPrecio && $editarPrecioErrors?->any())
                                <div class="md:col-span-2 rounded-lg border border-red-500/40 bg-red-500/10 px-4 py-3 text-sm text-red-200">
                                    <p class="font-semibold text-red-100">Revisa la vigencia ingresada</p>
                                    <p>{{ $editarPrecioErrors->first() }}</p>
                                </div>
                            @endif

                            <div class="md:col-span-2">
                                <label class="block text-xs uppercase tracking-widest text-emerald-400 mb-1">Cancha</label>
                                @php
                                    $editPrecioCanchaError = $rowIsEditingPrecio && $editarPrecioErrors?->has('cancha_id');
                                @endphp
                                <select name="cancha_id"
                                    class="w-full rounded-lg border bg-slate-900/70 px-3 py-2 focus:outline-none {{ $editPrecioCanchaError ? 'border-red-500 focus:ring focus:ring-red-500' : 'border-slate-600 focus:ring focus:ring-amber-500' }}"
                                    required>
                                    <option value="">Selecciona una cancha</option>
                                    @foreach ($canchas as $canchaOption)
                                        <option value="{{ $canchaOption->id }}" {{ $editCanchaId === $canchaOption->id ? 'selected' : '' }}>
                                            {{ $canchaOption->nombre }}
                                        </option>
                                    @endforeach
                                </select>
                                @if ($rowIsEditingPrecio && $editarPrecioErrors?->has('cancha_id'))
                                    <p class="text-xs text-red-300 mt-1">{{ $editarPrecioErrors->first('cancha_id') }}</p>
                                @endif
                            </div>

                            <div>
                                <label class="block text-xs uppercase tracking-widest text-emerald-400 mb-1">Precio por hora</label>
                                @php
                                    $editPrecioValorError = $rowIsEditingPrecio && $editarPrecioErrors?->has('precio_hora');
                                @endphp
                                <input type="number" min="0" step="0.01" name="precio_hora" value="{{ $editPrecioHora }}"
                                    class="w-full rounded-lg border bg-slate-900/70 px-3 py-2 focus:outline-none {{ $editPrecioValorError ? 'border-red-500 focus:ring focus:ring-red-500' : 'border-slate-600 focus:ring focus:ring-amber-500' }}"
                                    required>
                                @if ($rowIsEditingPrecio && $editarPrecioErrors?->has('precio_hora'))
                                    <p class="text-xs text-red-300 mt-1">{{ $editarPrecioErrors->first('precio_hora') }}</p>
                                @endif
                            </div>

                            <div>
                                <label class="block text-xs uppercase tracking-widest text-emerald-400 mb-1">Fecha y hora de inicio</label>
                                @php
                                    $editPrecioDesdeError = $rowIsEditingPrecio && $editarPrecioErrors?->has('fecha_desde');
                                @endphp
                                <input type="datetime-local" name="fecha_desde" value="{{ $editFechaDesde }}"
                                    class="w-full rounded-lg border bg-slate-900/70 px-3 py-2 focus:outline-none {{ $editPrecioDesdeError ? 'border-red-500 focus:ring focus:ring-red-500' : 'border-slate-600 focus:ring focus:ring-amber-500' }}"
                                    required>
                                @if ($rowIsEditingPrecio && $editarPrecioErrors?->has('fecha_desde'))
                                    <p class="text-xs text-red-300 mt-1">{{ $editarPrecioErrors->first('fecha_desde') }}</p>
                                @endif
                            </div>

                            <div class="md:col-span-2">
                                <label class="block text-xs uppercase tracking-widest text-emerald-400 mb-1">Fecha y hora de fin (opcional)</label>
                                @php
                                    $editPrecioHastaError = $rowIsEditingPrecio && $editarPrecioErrors?->has('fecha_hasta');
                                @endphp
                                <input type="datetime-local" name="fecha_hasta" value="{{ $editFechaHasta }}"
                                    class="w-full rounded-lg border bg-slate-900/70 px-3 py-2 focus:outline-none {{ $editPrecioHastaError ? 'border-red-500 focus:ring focus:ring-red-500' : 'border-slate-600 focus:ring focus:ring-amber-500' }}">
                                @if ($rowIsEditingPrecio && $editarPrecioErrors?->has('fecha_hasta'))
                                    <p class="text-xs text-red-300 mt-1">{{ $editarPrecioErrors->first('fecha_hasta') }}</p>
                                @endif
                            </div>

                            <div class="md:col-span-2 flex justify-end gap-3">
                                <button type="button" data-precio-edit-modal-close
                                    class="px-4 py-2 rounded-lg border border-slate-600 text-slate-300 hover:bg-slate-800 transition">
                                    Cancelar
                                </button>
                                <button type="submit"
                                    class="px-5 py-2 rounded-lg bg-green-600 hover:bg-green-700 text-slate-950 font-semibold transition">
                                    Guardar cambios
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        @endforeach

        <!-- MODAL ELIMINAR PRECIO -->
        <div id="precio-delete-modal"
            class="fixed inset-0 bg-black/70 backdrop-blur-sm hidden items-center justify-center z-50 px-4 py-8">
            <div class="relative w-full max-w-lg border border-amber-500/30 bg-slate-950/95 p-6 shadow-2xl text-white">
                <button type="button" data-precio-delete-cancel
                    class="absolute top-3 right-3 text-emerald-400 hover:text-white transition" aria-label="Cerrar">
                    ✕
                </button>
                <div class="space-y-4">
                    <div class="flex items-start gap-3">
                        <div class="rounded-full p-2 bg-amber-500/15 text-amber-300">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" stroke="currentColor"
                                stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M12 9v4" />
                                <path d="M12 17h.01" />
                                <path d="M10 3h4l7 12-2 4H5l-2-4 7-12z" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm uppercase tracking-[0.3em] text-amber-300">Confirmar acción</p>
                            <p class="text-lg font-semibold text-white">
                                ¿Eliminar esta vigencia de precio?
                            </p>
                            <p class="text-sm text-emerald-400 mt-1">Las reservas futuras utilizarán el precio vigente restante.</p>
                        </div>
                    </div>

                    <div class="flex justify-end gap-3">
                        <button type="button" data-precio-delete-cancel
                            class="px-4 py-2 rounded-lg border border-slate-600 text-slate-300 hover:bg-slate-800 transition">
                            Cancelar
                        </button>
                        <button type="button" data-precio-delete-confirm
                            class="px-5 py-2 rounded-lg bg-red-500 hover:bg-red-600 text-white font-semibold transition">
                            Eliminar
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- SECCIÓN USUARIOS -->
    <section id="usuarios" data-section="usuarios" class="scroll-mt-32 hidden">
        <div class="bg-gradient-to-br from-emerald-950 via-emerald-900 to-emerald-800 min-h-screen flex justify-center p-4 sm:p-6 md:p-10">
            <div class="w-full max-w-5xl space-y-4">
                <div class="flex flex-col gap-3 sm:gap-2 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <h1 class="text-lg sm:text-xl font-bold text-white">Usuarios del sistema</h1>
                        <p class="text-xs sm:text-sm text-emerald-400">Administra cuentas, roles y estados de acceso.</p>
                    </div>
                    <button id="abrirUsuarioModal" type="button"
                        class="flex items-center justify-center gap-2 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold px-4 py-2 rounded-lg shadow-md transition whitespace-nowrap">
                        <span class="text-xl font-bold">+</span>
                        <span class="hidden xs:inline">NUEVO USUARIO</span>
                        <span class="xs:hidden">NUEVO</span>
                    </button>
                </div>

                <div class="overflow-x-auto rounded-2xl border border-emerald-800/60 bg-emerald-950/70 shadow-2xl">
                <div class="bg-emerald-950/60 border border-emerald-800 rounded-2xl p-4 shadow-2xl">
                    <div class="grid gap-4 md:grid-cols-2">
                        <div>
                            <label for="usuario-search"
                                class="text-xs uppercase tracking-[0.3em] text-emerald-400">Buscar por nombre/correo</label>
                            <div class="relative mt-1">
                                <span class="absolute inset-y-0 left-3 flex items-center text-emerald-500">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                                        stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="m21 21-4.35-4.35m0 0A7 7 0 1 0 6.5 6.5a7 7 0 0 0 10.15 10.15z" />
                                    </svg>
                                </span>
                                <input id="usuario-search" type="search" placeholder="Ej. Ana, ana@mail.com"
                                    class="w-full rounded-xl border border-emerald-700 bg-emerald-900/70 pl-9 pr-3 py-2 text-sm text-white placeholder-emerald-500 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                                    data-usuario-search>
                            </div>
                        </div>

                        <div>
                            <label for="usuario-role-filter"
                                class="text-xs uppercase tracking-[0.3em] text-emerald-400">Filtrar por rol</label>
                            <select id="usuario-role-filter"
                                class="mt-1 w-full rounded-xl border border-emerald-700 bg-emerald-900/70 px-3 py-2 text-sm text-white focus:outline-none focus:ring-2 focus:ring-indigo-500"
                                data-usuario-role>
                                <option value="">Todos los roles</option>
                                @foreach ($usuarioRoles as $rolNombre)
                                    <option value="{{ $rolNombre }}">{{ ucfirst($rolNombre) }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <div class="overflow-x-auto rounded-2xl border border-slate-800 bg-slate-950/60 shadow-2xl">
                    <table class="w-full text-left text-sm text-gray-300">
                        <thead class="bg-emerald-950/60">
                            <tr class="uppercase text-xs text-emerald-400 tracking-wide">
                                <th class="px-6 py-3">Usuario</th>
                                <th class="px-6 py-3">Rol</th>
                                <th class="px-6 py-3 text-center">Estado</th>
                                <th class="px-6 py-3">Creado</th>
                                <th class="px-6 py-3 text-right">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-emerald-900/50">
                            @forelse ($usuarios as $usuario)
                                @php
                                    $rolLabel = optional($usuario->role)->description ?? optional($usuario->role)->name ?? 'Sin rol asignado';
                                    $isCurrentUser = auth()->id() === $usuario->id;
                                @endphp
                                <tr class="hover:bg-emerald-900/40 transition-colors"
                                    data-usuario-row
                                    data-usuario-nombre="{{ mb_strtolower($usuario->name) }}"
                                    data-usuario-email="{{ mb_strtolower($usuario->email) }}"
                                    data-usuario-rol="{{ mb_strtolower($rolLabel) }}">
                                    <td class="px-6 py-4">
                                        <p class="font-semibold text-white">{{ $usuario->name }}</p>
                                        <p class="text-xs text-emerald-400">{{ $usuario->email }}</p>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="inline-flex items-center rounded-full bg-emerald-500/10 text-emerald-200 px-3 py-1 text-xs font-medium">
                                            {{ ucfirst($rolLabel) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        @if ($usuario->activo)
                                            <span class="inline-flex items-center justify-center bg-emerald-500/15 text-emerald-300 px-3 py-1 rounded-full text-xs font-medium">
                                                Activo
                                            </span>
                                        @else
                                            <span class="inline-flex items-center justify-center bg-rose-500/10 text-rose-300 px-3 py-1 rounded-full text-xs font-medium">
                                                Inactivo
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4">
                                        <p class="font-semibold text-white">
                                            {{ optional($usuario->created_at)->format('d/m/Y') ?? '—' }}
                                        </p>
                                        <p class="text-xs text-emerald-400">ID #{{ $usuario->id }}</p>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center justify-end gap-3 text-lg">
                                            <button type="button"
                                                class="hover:text-emerald-300 transition-colors"
                                                data-usuario-edit-target="usuario-edit-modal-{{ $usuario->id }}"
                                                title="Editar usuario">
                                                <svg width="20" height="20" fill="currentColor" viewBox="0 0 24 24">
                                                    <path d="M3 17.25V21h3.75L17.81 9.94l-3.75-3.75L3 17.25zM21.41 6.34c.39-.39.39-1.02 0-1.41l-2.34-2.34a1 1 0 0 0-1.41 0l-1.83 1.83 3.75 3.75 1.83-1.83z" />
                                                </svg>
                                            </button>
                                            @if ($isCurrentUser)
                                                <span class="text-xs text-emerald-500 border border-slate-700 rounded-full px-3 py-1" title="No puedes eliminar tu propia cuenta">
                                                    Sesión activa
                                                </span>
                                            @else
                                                <form id="usuario-delete-form-{{ $usuario->id }}" method="POST"
                                                    action="{{ route('admin.usuarios.destroy', $usuario) }}">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="button" class="hover:text-rose-400 transition-colors"
                                                        data-usuario-delete-target="usuario-delete-form-{{ $usuario->id }}"
                                                        data-usuario-delete-name="{{ $usuario->name }}"
                                                        data-usuario-delete-email="{{ $usuario->email }}"
                                                        title="Eliminar usuario">
                                                        <svg width="20" height="20" fill="currentColor" viewBox="0 0 24 24">
                                                            <path d="M6 19a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2V7H6v12zm3-9h2v7H9V10zm4 0h2v7h-2v-7z" />
                                                            <path d="M15.5 4l-1-1h-5l-1 1H5v2h14V4z" />
                                                        </svg>
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-10 text-center text-emerald-400">
                                        Aún no hay usuarios registrados.
                                    </td>
                                </tr>
                            @endforelse
                            @if ($usuarios->isNotEmpty())
                                <tr class="hidden" data-usuario-empty-row>
                                    <td colspan="5" class="px-6 py-10 text-center text-emerald-400">
                                        No se encontraron usuarios que coincidan con los filtros aplicados.
                                    </td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- MODAL CREAR USUARIO -->
        <div id="usuario-modal"
            class="fixed inset-0 bg-black/60 backdrop-blur-sm hidden items-center justify-center z-50 px-4 py-8">
            <div
                class="relative w-full max-w-3xl border border-slate-800 bg-slate-950/95 p-8 shadow-2xl max-h-[90vh] overflow-y-auto">
                <button type="button" data-usuario-modal-close
                    class="absolute top-4 right-4 bg-emerald-600 text-white w-10 h-10 rounded-full shadow-lg flex items-center justify-center text-2xl font-bold hover:bg-red-500 transition">
                    ✕
                </button>

                <div class="space-y-6 text-white">
                    <div>
                        <p class="text-sm uppercase tracking-[0.3em] text-emerald-300">Nuevo usuario</p>
                        <h2 class="text-2xl font-semibold">Registrar cuenta</h2>
                        <p class="text-emerald-400 text-sm">Define los datos básicos y el rol que tendrá dentro del sistema.</p>
                    </div>

                    <form method="POST" action="{{ route('admin.usuarios.store') }}" class="grid gap-4 md:grid-cols-2 text-sm">
                        @csrf

                        <div>
                            <label class="block text-xs uppercase tracking-widest text-emerald-400 mb-1">Nombre completo</label>
                            <input type="text" name="name" value="{{ old('name') }}"
                                class="w-full rounded-lg border border-slate-600 bg-slate-900/70 px-3 py-2 focus:outline-none focus:ring focus:ring-emerald-500"
                                required>
                            @error('name', 'crearUsuario')
                                <p class="text-xs text-rose-300 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-xs uppercase tracking-widest text-emerald-400 mb-1">Correo electrónico</label>
                            <input type="email" name="email" value=""
                                autocomplete="off"
                                class="w-full rounded-lg border border-slate-600 bg-slate-900/70 px-3 py-2 focus:outline-none focus:ring focus:ring-emerald-500"
                                required>
                            @error('email', 'crearUsuario')
                                <p class="text-xs text-rose-300 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-xs uppercase tracking-widest text-emerald-400 mb-1">Rol</label>
                            <select name="role_id"
                                class="w-full rounded-lg border border-slate-600 bg-slate-900/70 px-3 py-2 focus:outline-none focus:ring focus:ring-emerald-500"
                                required>
                                <option value="">Selecciona un rol</option>
                                @foreach ($roles as $rol)
                                    <option value="{{ $rol->id }}" @selected(old('role_id') == $rol->id)>
                                        {{ $rol->description ?? ucfirst($rol->name) }}
                                    </option>
                                @endforeach
                            </select>
                            @error('role_id', 'crearUsuario')
                                <p class="text-xs text-rose-300 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-xs uppercase tracking-widest text-emerald-400 mb-1">Contraseña</label>
                            <input type="password" name="password"
                                autocomplete="new-password"
                                class="w-full rounded-lg border border-slate-600 bg-slate-900/70 px-3 py-2 focus:outline-none focus:ring focus:ring-emerald-500"
                                required>
                            @error('password', 'crearUsuario')
                                <p class="text-xs text-rose-300 mt-1">{{ $message }}</p>
                            @enderror
                            <p class="text-xs text-emerald-500 mt-1">
                                Debe tener al menos 8 caracteres y combinar letras, números o símbolos.
                            </p>
                        </div>

                        <div class="md:col-span-2 flex items-center gap-3">
                            <input type="hidden" name="activo" value="0">
                            <input type="checkbox" id="usuario_activo_create" name="activo" value="1"
                                class="h-4 w-4 rounded border-slate-500 bg-slate-900 text-emerald-500 focus:ring-emerald-500"
                                {{ old('activo', 1) ? 'checked' : '' }}>
                            <label for="usuario_activo_create" class="text-xs uppercase tracking-widest text-emerald-400">
                                Usuario activo
                            </label>
                        </div>
                        @error('activo', 'crearUsuario')
                            <div class="md:col-span-2 text-xs text-rose-300">
                                {{ $message }}
                            </div>
                        @enderror

                        <div class="md:col-span-2 flex justify-end gap-3">
                            <button type="button" data-usuario-modal-close
                                class="px-4 py-2 rounded-lg border border-slate-600 text-slate-300 hover:bg-slate-800 transition">Cancelar</button>
                            <button type="submit"
                                class="px-5 py-2 rounded-lg bg-indigo-500 hover:bg-emerald-700 text-white font-semibold transition">
                                Guardar usuario
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- MODALES EDITAR USUARIO -->
        @foreach ($usuarios as $usuario)
            @php
                $usuarioRowEditing = (int) $editarUsuarioId === $usuario->id;
                $editUsuarioNombre = $usuarioRowEditing ? old('name', $usuario->name) : $usuario->name;
                $editUsuarioCorreo = $usuarioRowEditing ? old('email', $usuario->email) : $usuario->email;
                $editUsuarioRol = $usuarioRowEditing ? (int) old('role_id', $usuario->role_id) : $usuario->role_id;
                $editUsuarioActivo = $usuarioRowEditing ? (bool) old('activo', $usuario->activo) : (bool) $usuario->activo;
                $isCurrentUser = auth()->id() === $usuario->id;
            @endphp
            <div id="usuario-edit-modal-{{ $usuario->id }}"
                class="usuario-edit-modal fixed inset-0 bg-black/60 backdrop-blur-sm hidden items-center justify-center z-50 px-4 py-8"
                data-usuario-edit-modal data-open-default="{{ $usuarioRowEditing ? 'true' : 'false' }}">
                <div
                    class="relative w-full max-w-3xl border border-slate-800 bg-slate-950/95 p-8 shadow-2xl max-h-[90vh] overflow-y-auto text-white">
                    <button type="button" data-usuario-edit-modal-close
                        class="absolute top-4 right-4 bg-emerald-600 text-white w-10 h-10 rounded-full shadow-lg flex items-center justify-center text-2xl font-bold hover:bg-red-500 transition">
                        ✕
                    </button>

                    <div class="space-y-6">
                        <div>
                            <p class="text-sm uppercase tracking-[0.3em] text-emerald-300">Editar usuario</p>
                            <h2 class="text-2xl font-semibold">Actualizar {{ $usuario->name }}</h2>
                            <p class="text-emerald-400 text-sm">Modifica los datos necesarios y guarda los cambios.</p>
                        </div>

                        <form method="POST" action="{{ route('admin.usuarios.update', $usuario) }}" class="grid gap-4 md:grid-cols-2 text-sm">
                            @csrf
                            @method('PUT')

                            <div>
                                <label class="block text-xs uppercase tracking-widest text-emerald-400 mb-1">Nombre completo</label>
                                <input type="text" name="name" value="{{ $editUsuarioNombre }}"
                                    class="w-full rounded-lg border border-slate-600 bg-slate-900/70 px-3 py-2 focus:outline-none focus:ring focus:ring-emerald-500"
                                    required>
                                @if ($usuarioRowEditing && $usuarioEditErrors?->has('name'))
                                    <p class="text-xs text-rose-300 mt-1">{{ $usuarioEditErrors->first('name') }}</p>
                                @endif
                            </div>

                            <div>
                                <label class="block text-xs uppercase tracking-widest text-emerald-400 mb-1">Correo electrónico</label>
                                <input type="email" name="email" value="{{ $editUsuarioCorreo }}"
                                    class="w-full rounded-lg border border-slate-600 bg-slate-900/70 px-3 py-2 focus:outline-none focus:ring focus:ring-emerald-500"
                                    required>
                                @if ($usuarioRowEditing && $usuarioEditErrors?->has('email'))
                                    <p class="text-xs text-rose-300 mt-1">{{ $usuarioEditErrors->first('email') }}</p>
                                @endif
                            </div>

                            <div>
                                <label class="block text-xs uppercase tracking-widest text-emerald-400 mb-1">Rol</label>
                                <select name="role_id"
                                    class="w-full rounded-lg border border-slate-600 bg-slate-900/70 px-3 py-2 focus:outline-none focus:ring focus:ring-emerald-500"
                                    required>
                                    <option value="">Selecciona un rol</option>
                                    @foreach ($roles as $rol)
                                        <option value="{{ $rol->id }}" @selected($editUsuarioRol == $rol->id)>
                                            {{ $rol->description ?? ucfirst($rol->name) }}
                                        </option>
                                    @endforeach
                                </select>
                                @if ($usuarioRowEditing && $usuarioEditErrors?->has('role_id'))
                                    <p class="text-xs text-rose-300 mt-1">{{ $usuarioEditErrors->first('role_id') }}</p>
                                @endif
                            </div>

                            <div>
                                <label class="block text-xs uppercase tracking-widest text-emerald-400 mb-1">Contraseña</label>
                                <input type="password" name="password" value=""
                                    autocomplete="new-password"
                                    class="w-full rounded-lg border border-slate-600 bg-slate-900/70 px-3 py-2 focus:outline-none focus:ring focus:ring-emerald-500"
                                    placeholder="Dejar en blanco para mantener">
                            @if ($usuarioRowEditing && $usuarioEditErrors?->has('password'))
                                <p class="text-xs text-rose-300 mt-1">{{ $usuarioEditErrors->first('password') }}</p>
                            @endif
                                <p class="text-xs text-emerald-500 mt-1">
                                    Debe tener mínimo 8 caracteres. Deja el campo vacío para conservar la actual.
                                </p>
                            </div>

                            <div class="md:col-span-2 flex flex-col gap-2">
                                <div class="flex items-center gap-3">
                                    <input type="hidden" name="activo" value="{{ $isCurrentUser ? (int) $editUsuarioActivo : 0 }}">
                                    <input type="checkbox" id="usuario_activo_{{ $usuario->id }}" name="activo" value="1"
                                        class="h-4 w-4 rounded border-slate-500 bg-slate-900 text-emerald-500 focus:ring-emerald-500 disabled:opacity-50 disabled:cursor-not-allowed"
                                        {{ $editUsuarioActivo ? 'checked' : '' }} {{ $isCurrentUser ? 'disabled' : '' }}>
                                    <label for="usuario_activo_{{ $usuario->id }}"
                                        class="text-xs uppercase tracking-widest text-emerald-400">Usuario activo</label>
                                </div>
                                @if ($isCurrentUser)
                                    <p class="text-xs text-emerald-500">
                                        No puedes desactivar tu propia cuenta mientras la sesión esté activa.
                                    </p>
                                @endif
                            </div>
                            @if ($usuarioRowEditing && $usuarioEditErrors?->has('activo'))
                                <div class="md:col-span-2 text-xs text-rose-300">
                                    {{ $usuarioEditErrors->first('activo') }}
                                </div>
                            @endif

                            <div class="md:col-span-2 flex justify-end gap-3">
                                <button type="button" data-usuario-edit-modal-close
                                    class="px-4 py-2 rounded-lg border border-slate-600 text-slate-300 hover:bg-slate-800 transition">Cancelar</button>
                                <button type="submit"
                                    class="px-5 py-2 rounded-lg bg-indigo-500 hover:bg-emerald-700 text-white font-semibold transition">
                                    Guardar cambios
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        @endforeach

        <!-- MODAL ELIMINAR USUARIO -->
        <div id="usuario-delete-modal"
            class="fixed inset-0 bg-black/70 backdrop-blur-sm hidden items-center justify-center z-50 px-4 py-8">
            <div class="relative w-full max-w-lg border border-rose-500/40 bg-slate-950/95 p-6 shadow-2xl text-white">
                <button type="button" data-usuario-delete-cancel
                    class="absolute top-3 right-3 text-emerald-400 hover:text-white transition" aria-label="Cerrar">
                    ✕
                </button>
                <div class="space-y-4">
                    <div class="flex items-start gap-3">
                        <div class="rounded-full p-2 bg-rose-500/15 text-rose-300">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" stroke="currentColor"
                                stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M12 9v4" />
                                <path d="M12 17h.01" />
                                <path d="M10 3h4l7 12-2 4H5l-2-4 7-12z" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm uppercase tracking-[0.3em] text-rose-300">Confirmar acción</p>
                            <p class="text-lg font-semibold text-white">
                                ¿Eliminar este usuario?
                            </p>
                            <p class="text-sm text-emerald-400 mt-1">
                                Se revocará su acceso inmediatamente. Esta acción no se puede deshacer.
                            </p>
                        </div>
                    </div>

                    <div class="rounded-lg border border-slate-800 bg-slate-900/60 p-4">
                        <p class="text-sm text-emerald-400">Usuario</p>
                        <p class="text-lg font-semibold text-white" data-usuario-delete-name>—</p>
                        <p class="text-xs text-emerald-500" data-usuario-delete-email>—</p>
                    </div>

                    <div class="flex justify-end gap-3">
                        <button type="button" data-usuario-delete-cancel
                            class="px-4 py-2 rounded-lg border border-slate-600 text-slate-300 hover:bg-slate-800 transition">
                            Cancelar
                        </button>
                        <button type="button" data-usuario-delete-confirm
                            class="px-5 py-2 rounded-lg bg-rose-500 hover:bg-rose-600 text-white font-semibold transition">
                            Eliminar
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

{{-- pasar variables de PHP a JavaScript ignorar error del editor --}}

<script>
    window.reservasConfig = {
        shouldOpenCreateModal: @json($shouldOpenCreateModal),
        serverEditId: @json($editarCanchaId),
        shouldOpenBloqueoModal: @json($shouldOpenBloqueoModal),
        serverBloqueoEditId: @json($editarBloqueoId),
        shouldOpenPrecioModal: @json($shouldOpenPrecioModal),
        serverPrecioEditId: @json($editarPrecioId),
        shouldOpenUsuarioModal: @json($shouldOpenUsuarioModal),
        serverUsuarioEditId: @json($editarUsuarioId),
        serverReservaEditId: @json($editarReservaId),
    };
</script>
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const normalise = (value = '') => {
            const text = (value ?? '').toString().toLowerCase();
            return text.normalize ? text.normalize('NFD').replace(/[\u0300-\u036f]/g, '') : text;
        };

        (function setupCanchaFilters() {
            const searchInput = document.querySelector('[data-cancha-search]');
            const typeFilter = document.querySelector('[data-cancha-type-filter]');
            if (!searchInput || !typeFilter) {
                return;
            }

            const tableRows = Array.from(document.querySelectorAll('[data-cancha-row]'));
            const cardItems = Array.from(document.querySelectorAll('[data-cancha-card]'));
            const tableEmptyRow = document.querySelector('[data-cancha-empty-row]');
            const mobileEmptyCard = document.querySelector('[data-cancha-empty-mobile]');

            const applyFilters = () => {
                const searchTerm = normalise(searchInput.value.trim());
                const selectedType = normalise(typeFilter.value).trim();
                let matchesCount = 0;

                const matchesCancha = (nombre, tipo) => {
                    const normalisedName = normalise(nombre);
                    const normalisedType = normalise(tipo);
                    const byName = !searchTerm || normalisedName.includes(searchTerm);
                    const byType = !selectedType || normalisedType === selectedType;
                    return byName && byType;
                };

                tableRows.forEach((row) => {
                    const nombre = row.dataset.canchaNombre || '';
                    const tipo = row.dataset.canchaTipo || '';
                    const matches = matchesCancha(nombre, tipo);
                    row.classList.toggle('hidden', !matches);
                    if (matches) {
                        matchesCount++;
                    }
                });

                cardItems.forEach((card) => {
                    const nombre = card.dataset.canchaNombre || '';
                    const tipo = card.dataset.canchaTipo || '';
                    const matches = matchesCancha(nombre, tipo);
                    card.classList.toggle('hidden', !matches);
                });

                const noResults = matchesCount === 0 && (tableRows.length > 0 || cardItems.length > 0);
                if (tableEmptyRow) {
                    tableEmptyRow.classList.toggle('hidden', !noResults);
                }
                if (mobileEmptyCard) {
                    mobileEmptyCard.classList.toggle('hidden', !noResults);
                }
            };

            searchInput.addEventListener('input', applyFilters);
            typeFilter.addEventListener('change', applyFilters);
            applyFilters();
        })();

        (function setupReservaFilters() {
            const canchaSearch = document.querySelector('[data-reserva-cancha-search]');
            const tipoFilter = document.querySelector('[data-reserva-tipo-filter]');
            const clienteSearch = document.querySelector('[data-reserva-cliente-search]');
            if (!canchaSearch || !tipoFilter || !clienteSearch) {
                return;
            }

            const rows = Array.from(document.querySelectorAll('[data-reserva-row]'));
            const emptyRow = document.querySelector('[data-reserva-empty-row]');

            const applyReservaFilters = () => {
                const canchaTerm = normalise(canchaSearch.value.trim());
                const tipoTerm = normalise(tipoFilter.value).trim();
                const clienteTerm = normalise(clienteSearch.value.trim());
                let matchesCount = 0;

                rows.forEach((row) => {
                    const canchaNombre = row.dataset.reservaCancha || '';
                    const tipoNombre = row.dataset.reservaTipo || '';
                    const clienteNombre = row.dataset.reservaCliente || '';
                    const byCancha = !canchaTerm || normalise(canchaNombre).includes(canchaTerm);
                    const byTipo = !tipoTerm || normalise(tipoNombre) === tipoTerm;
                    const byCliente = !clienteTerm || normalise(clienteNombre).includes(clienteTerm);
                    const matches = byCancha && byTipo && byCliente;
                    row.classList.toggle('hidden', !matches);
                    if (matches) {
                        matchesCount++;
                    }
                });

                if (emptyRow) {
                    const shouldShowEmpty = rows.length > 0 && matchesCount === 0;
                    emptyRow.classList.toggle('hidden', !shouldShowEmpty);
                }
            };

            canchaSearch.addEventListener('input', applyReservaFilters);
            tipoFilter.addEventListener('change', applyReservaFilters);
            clienteSearch.addEventListener('input', applyReservaFilters);
            applyReservaFilters();
        })();

        (function setupBloqueoFilters() {
            const searchInput = document.querySelector('[data-bloqueo-search]');
            const typeFilter = document.querySelector('[data-bloqueo-type]');
            if (!searchInput || !typeFilter) {
                return;
            }

            const rows = Array.from(document.querySelectorAll('[data-bloqueo-row]'));
            const emptyRow = document.querySelector('[data-bloqueo-empty-row]');

            const applyFilters = () => {
                const searchTerm = normalise(searchInput.value.trim());
                const typeTerm = normalise(typeFilter.value).trim();
                let matches = 0;

                rows.forEach((row) => {
                    const canchaNombre = row.dataset.bloqueoCancha || '';
                    const canchaTipo = row.dataset.bloqueoTipo || '';
                    const fitsName = !searchTerm || normalise(canchaNombre).includes(searchTerm);
                    const fitsType = !typeTerm || normalise(canchaTipo) === typeTerm;
                    const shouldShow = fitsName && fitsType;
                    row.classList.toggle('hidden', !shouldShow);
                    if (shouldShow) {
                        matches++;
                    }
                });

                if (emptyRow) {
                    emptyRow.classList.toggle('hidden', !(rows.length > 0 && matches === 0));
                }
            };

            searchInput.addEventListener('input', applyFilters);
            typeFilter.addEventListener('change', applyFilters);
            applyFilters();
        })();

        (function setupPrecioFilters() {
            const searchInput = document.querySelector('[data-precio-search]');
            const typeFilter = document.querySelector('[data-precio-type]');
            if (!searchInput || !typeFilter) {
                return;
            }

            const rows = Array.from(document.querySelectorAll('[data-precio-row]'));
            const emptyRow = document.querySelector('[data-precio-empty-row]');

            const applyFilters = () => {
                const nameTerm = normalise(searchInput.value.trim());
                const typeTerm = normalise(typeFilter.value).trim();
                let matches = 0;

                rows.forEach((row) => {
                    const canchaNombre = row.dataset.precioCancha || '';
                    const canchaTipo = row.dataset.precioTipo || '';
                    const fitsName = !nameTerm || normalise(canchaNombre).includes(nameTerm);
                    const fitsType = !typeTerm || normalise(canchaTipo) === typeTerm;
                    const shouldShow = fitsName && fitsType;
                    row.classList.toggle('hidden', !shouldShow);
                    if (shouldShow) {
                        matches++;
                    }
                });

                if (emptyRow) {
                    emptyRow.classList.toggle('hidden', !(rows.length > 0 && matches === 0));
                }
            };

            searchInput.addEventListener('input', applyFilters);
            typeFilter.addEventListener('change', applyFilters);
            applyFilters();
        })();

        (function setupUsuarioFilters() {
            const searchInput = document.querySelector('[data-usuario-search]');
            const roleFilter = document.querySelector('[data-usuario-role]');
            if (!searchInput || !roleFilter) {
                return;
            }

            const rows = Array.from(document.querySelectorAll('[data-usuario-row]'));
            const emptyRow = document.querySelector('[data-usuario-empty-row]');

            const applyFilters = () => {
                const searchTerm = normalise(searchInput.value.trim());
                const roleTerm = normalise(roleFilter.value).trim();
                let matches = 0;

                rows.forEach((row) => {
                    const nombre = row.dataset.usuarioNombre || '';
                    const email = row.dataset.usuarioEmail || '';
                    const rol = row.dataset.usuarioRol || '';
                    const fitsName = !searchTerm || normalise(nombre).includes(searchTerm) || normalise(email).includes(searchTerm);
                    const fitsRole = !roleTerm || normalise(rol) === roleTerm;
                    const shouldShow = fitsName && fitsRole;
                    row.classList.toggle('hidden', !shouldShow);
                    if (shouldShow) {
                        matches++;
                    }
                });

                if (emptyRow) {
                    emptyRow.classList.toggle('hidden', !(rows.length > 0 && matches === 0));
                }
            };

            searchInput.addEventListener('input', applyFilters);
            roleFilter.addEventListener('change', applyFilters);
            applyFilters();
        })();
    });
</script>

{{-- logica --}}
<script src="{{ asset('js/admin-calendar.js') }}?v={{ $adminCalendarJsVersion }}"></script>
<script src="{{ asset('js/reservas.js') }}?v={{ $reservasJsVersion }}"></script>
@endsection
