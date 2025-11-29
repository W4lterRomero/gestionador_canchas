@extends('components.layouts.app')

@php
    $canchas = $canchas ?? collect();
    $bloqueos = $bloqueos ?? collect();
    $precios = $precios ?? collect();
    $editarCanchaId = session('editarCanchaId');
    $editarBloqueoId = session('editarBloqueoId');
    $editarPrecioId = session('editarPrecioId');
    $shouldOpenCreateModal = $errors->crearCancha->any();
    $shouldOpenBloqueoModal = $errors->crearBloqueo->any();
    $shouldOpenPrecioModal = $errors->crearPrecio->any();
    $crearBloqueoErrors = $errors->crearBloqueo ?? null;
    $editarBloqueoErrors = $errors->editarBloqueo ?? null;
    $crearPrecioErrors = $errors->crearPrecio ?? null;
    $editarPrecioErrors = $errors->editarPrecio ?? null;
    $feedbackStatus = session('status');
    $feedbackError = session('error');
    $feedbackMessage = $feedbackStatus ?: $feedbackError;
    $feedbackType = $feedbackStatus ? 'success' : ($feedbackError ? 'error' : null);
    $isErrorFeedback = $feedbackType === 'error';
    $feedbackTitle = $isErrorFeedback ? 'Ocurrió un problema' : 'Operación exitosa';
@endphp

@section('content')
<header class="bg-gradient-to-r from-slate-900 via-blue-800 to-blue-500 text-white p-8 shadow-xl">
    <div class="flex flex-col gap-2">
        <p class="text-sm uppercase tracking-[0.3em] text-blue-200">PANEL DE ADMINISTRADOR</p>
    </div>
</header>
{{-- NAVBAR --}}
<nav id="admin-panels-nav"
    class="sticky top-4 z-10 bg-white shadow-lg border border-gray-200 px-5 py-3">
    <ul class="flex w-full flex-wrap items-center h-10 -mx-1 text-sm font-medium">

        {{-- CANCHAS --}}
        <li class="block">
            <button type="button"
                data-section-target="canchas"
                data-default="true"
                class="panel-tab flex items-center h-10 leading-10 px-4 rounded-md mx-1 cursor-pointer
                           transition-colors duration-100 
                           bg-indigo-200 text-gray-900 shadow hover:bg-indigo-300">
                <span class="mr-3 text-xl">
                    {{-- Icono cancha --}}
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-900" viewBox="0 0 24 24"
                        fill="none" stroke="currentColor" stroke-width="1.7"
                        stroke-linecap="round" stroke-linejoin="round">
                        <rect x="3" y="5" width="18" height="14" rx="2" />
                        <path d="M12 5v14M7 12h10" />
                        <circle cx="12" cy="12" r="2" />
                    </svg>
                </span>
                Canchas
            </button>
        </li>

        {{-- RESERVAS --}}
        <li class="block">
            <button type="button"
                data-section-target="reservas"
                class="panel-tab flex items-center h-10 leading-10 px-4 rounded-md mx-1 cursor-pointer
                           transition-colors duration-100 
                           bg-white text-gray-900 hover:bg-gray-100">
                <span class="mr-3 text-xl">
                    {{-- Icono calendario --}}
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-900" viewBox="0 0 24 24"
                        fill="none" stroke="currentColor" stroke-width="1.7"
                        stroke-linecap="round" stroke-linejoin="round">
                        <rect x="3" y="4" width="18" height="17" rx="2" />
                        <path d="M8 2v4M16 2v4M3 10h18" />
                    </svg>
                </span>
                Reservas
            </button>
        </li>

        {{-- BLOQUEOS --}}
        <li class="block">
            <button type="button"
                data-section-target="bloqueos"
                class="panel-tab flex items-center h-10 leading-10 px-4 rounded-md mx-1 cursor-pointer
                           transition-colors duration-100 
                           bg-white text-gray-900 hover:bg-gray-100">
                <span class="mr-3 text-xl">
                    {{-- Icono candado --}}
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-900" viewBox="0 0 24 24"
                        fill="none" stroke="currentColor" stroke-width="1.7"
                        stroke-linecap="round" stroke-linejoin="round">
                        <rect x="4" y="10" width="16" height="10" rx="2" />
                        <path d="M8 10V8a4 4 0 118 0v2" />
                        <circle cx="12" cy="15" r="1.4" />
                    </svg>
                </span>
                Bloqueos
            </button>
        </li>

        {{-- PRECIOS --}}
        <li class="block">
            <button type="button"
                data-section-target="precios"
                class="panel-tab flex items-center h-10 leading-10 px-4 rounded-md mx-1 cursor-pointer
                           transition-colors duration-100 
                           bg-white text-gray-900 hover:bg-gray-100">
                <span class="mr-3 text-xl">
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
                Precios
            </button>
        </li>

    </ul>
</nav>



<div>
    <!-- SECCIÓN CANCHAS -->
    <section id="canchas" data-section="canchas" class="scroll-mt-32">
        <div class="bg-slate-900 min-h-screen flex justify-center p-10">
            <div class="w-full max-w-5xl space-y-4">
                <!-- ENCABEZADO + BOTÓN -->
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-xl font-bold text-white">Canchas registradas</h1>
                    </div>

                    <button id="abrirModal" type="button"
                        class="flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold px-4 py-2 rounded-lg shadow-md transition">
                        <span class="text-xl font-bold">+</span>
                        AGREGAR
                    </button>
                </div>

                <!-- TABLA EN TARJETA -->
                <div class="overflow-x-auto rounded-2xl border border-slate-800 bg-slate-950/60 shadow-2xl">
                    <table class="w-full text-left text-sm text-gray-300">
                        <thead class="bg-slate-900/80">
                            <tr class="uppercase text-xs text-slate-400 tracking-wide">
                                <th class="px-6 py-3">Nombre</th>
                                <th class="px-6 py-3">Tipo</th>
                                <th class="px-6 py-3">Descripción</th>
                                <th class="px-6 py-3 text-right">Precio/Hora</th>
                                <th class="px-6 py-3">Imagen</th>
                                <th class="px-6 py-3 text-center">Activa</th>
                                <th class="px-6 py-3 text-right">Acciones</th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-slate-800">
                            @forelse ($canchas as $cancha)
                                @php
                                    $rowIsEditing = (int) $editarCanchaId === $cancha->id;
                                    $editNombre = $rowIsEditing ? old('nombre', $cancha->nombre) : $cancha->nombre;
                                    $editTipo = $rowIsEditing ? old('tipo', $cancha->tipo) : $cancha->tipo;
                                    $editDescripcion = $rowIsEditing ? old('descripcion', $cancha->descripcion) : $cancha->descripcion;
                                    $editPrecio = $rowIsEditing ? old('precio_hora', $cancha->precio_hora) : $cancha->precio_hora;
                                    $editActiva = $rowIsEditing ? (bool) old('activa', $cancha->activa) : (bool) $cancha->activa;
                                @endphp
                                <tr class="hover:bg-slate-900/70 transition-colors">
                                    <td class="px-6 py-4 font-semibold text-white">
                                        {{ $cancha->nombre }}
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="inline-flex items-center rounded-full bg-sky-500/10 text-sky-300 px-3 py-1 text-xs font-medium">
                                            {{ $cancha->tipo }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 max-w-xs">
                                        <p class="text-sm text-slate-300">
                                            {{ $cancha->descripcion ?: 'Sin descripción registrada' }}
                                        </p>
                                    </td>
                                    <td class="px-6 py-4 text-right font-semibold text-emerald-400">
                                        ${{ number_format($cancha->precio_hora, 2, '.', ',') }}
                                    </td>
                                    <td class="px-6 py-4">
                                        @php
                                            $imagenPath = $cancha->imagen_url ? ltrim($cancha->imagen_url, '/') : null;
                                        @endphp
                                        @if ($imagenPath)
                                            <img src="{{ asset($imagenPath) }}" alt="Imagen de {{ $cancha->nombre }}"
                                                class="h-16 w-24 rounded-lg border border-slate-800 object-cover shadow">
                                        @else
                                            <span class="text-xs font-mono text-slate-400">
                                                Sin imagen
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        @if ($cancha->activa)
                                            <span class="inline-flex items-center justify-center bg-green-500/15 text-green-400 px-3 py-1 rounded-full text-xs font-medium">
                                                Disponible
                                            </span>
                                        @else
                                            <span class="inline-flex items-center justify-center bg-red-500/10 text-red-300 px-3 py-1 rounded-full text-xs font-medium">
                                                Inactiva
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center justify-end gap-3 text-lg">
                                            <button type="button"
                                                class="hover:text-blue-400 transition-colors edit-toggle"
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
                                                <button type="button" class="hover:text-red-400 transition-colors"
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
                                    <td colspan="7" class="px-6 py-10 text-center text-slate-400">
                                        Aún no hay canchas registradas.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- MODAL PARA CREAR CANCHA -->
        <div id="modal"
            class="fixed inset-0 bg-black/60 backdrop-blur-sm hidden items-center justify-center z-50 px-4 py-8">
            <div
                class="relative w-full max-w-5xl border border-slate-800 bg-slate-950/95 p-8 shadow-2xl max-h-[90vh] overflow-y-auto">
                <button type="button" data-modal-close
                    class="absolute top-4 right-4 bg-blue-500 text-white w-10 h-10 rounded-full shadow-lg flex items-center justify-center text-2xl font-bold hover:bg-red-500 transition">
                    ✕
                </button>

                <div class="space-y-6 text-white">
                    <div>
                        <p class="text-sm uppercase tracking-[0.3em] text-blue-300">Nueva cancha</p>
                        <h2 class="text-2xl font-semibold">Registrar una cancha</h2>
                        <p class="text-slate-400 text-sm">Configura los datos básicos para que pueda reservarse en el
                            sistema.</p>
                    </div>

                    <form method="POST" action="{{ route('admin.canchas.store') }}" enctype="multipart/form-data"
                        class="grid gap-4 md:grid-cols-2 text-sm">
                        @csrf

                        <div>
                            <label class="block text-xs uppercase tracking-widest text-slate-400 mb-1">Nombre</label>
                            <input type="text" name="nombre" value="{{ old('nombre') }}"
                                class="w-full rounded-lg border border-slate-600 bg-slate-900/70 px-3 py-2 focus:outline-none focus:ring focus:ring-blue-500"
                                required>
                            @error('nombre', 'crearCancha')
                                <p class="text-xs text-red-300 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-xs uppercase tracking-widest text-slate-400 mb-1">Tipo</label>
                            <input type="text" name="tipo" value="{{ old('tipo') }}"
                                class="w-full rounded-lg border border-slate-600 bg-slate-900/70 px-3 py-2 focus:outline-none focus:ring focus:ring-blue-500"
                                required>
                            @error('tipo', 'crearCancha')
                                <p class="text-xs text-red-300 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-xs uppercase tracking-widest text-slate-400 mb-1">Descripción</label>
                            <textarea name="descripcion" rows="3"
                                class="w-full rounded-lg border border-slate-600 bg-slate-900/70 px-3 py-2 focus:outline-none focus:ring focus:ring-blue-500"
                                placeholder="Cuenta qué hace especial a la cancha">{{ old('descripcion') }}</textarea>
                            @error('descripcion', 'crearCancha')
                                <p class="text-xs text-red-300 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-xs uppercase tracking-widest text-slate-400 mb-1">Precio por hora</label>
                            <input type="number" name="precio_hora" min="0" step="0.01" value="{{ old('precio_hora') }}"
                                class="w-full rounded-lg border border-slate-600 bg-slate-900/70 px-3 py-2 focus:outline-none focus:ring focus:ring-blue-500"
                                required>
                            @error('precio_hora', 'crearCancha')
                                <p class="text-xs text-red-300 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-xs uppercase tracking-widest text-slate-400 mb-1">Imagen</label>
                            <input type="file" name="imagen" accept="image/*"
                                class="w-full rounded-lg border border-slate-600 bg-slate-900/70 px-3 py-2 text-sm file:bg-slate-800 file:text-slate-200 file:border-0 file:px-4 file:py-2 focus:outline-none focus:ring focus:ring-blue-500"
                                required>
                            @error('imagen', 'crearCancha')
                                <p class="text-xs text-red-300 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex items-center gap-3">
                            <input type="hidden" name="activa" value="0">
                            <input type="checkbox" id="modal_activa" name="activa" value="1"
                                class="h-4 w-4 rounded border-slate-500 bg-slate-900 text-blue-500 focus:ring-blue-500"
                                {{ old('activa') ? 'checked' : '' }}>
                            <label for="modal_activa" class="text-xs uppercase tracking-widest text-slate-400">Activa para
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
                                class="px-5 py-2 rounded-lg bg-blue-600 hover:bg-blue-700 text-white font-semibold transition">Guardar
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
                class="edit-modal fixed inset-0 bg-black/60 backdrop-blur-sm hidden items-center justify-center z-50 px-4 py-8"
                data-edit-modal data-open-default="{{ $rowIsEditing ? 'true' : 'false' }}">
                <div
                    class="relative w-full max-w-5xl border border-slate-800 bg-slate-950/95 p-8 shadow-2xl max-h-[90vh] overflow-y-auto text-white">
                    <button type="button" data-edit-modal-close
                        class="absolute top-4 right-4 bg-blue-500 text-white w-10 h-10 rounded-full shadow-lg flex items-center justify-center text-2xl font-bold hover:bg-red-500 transition">
                        ✕
                    </button>

                    <div class="space-y-6">
                        <div>
                            <p class="text-sm uppercase tracking-[0.3em] text-blue-300">Editar cancha</p>
                            <h2 class="text-2xl font-semibold">Actualizar {{ $cancha->nombre }}</h2>
                            <p class="text-slate-400 text-sm">Modifica la información y guarda los cambios.</p>
                        </div>

                        <form method="POST" action="{{ route('admin.canchas.update', $cancha) }}" enctype="multipart/form-data"
                            class="grid gap-4 md:grid-cols-2 text-sm">
                            @csrf
                            @method('PUT')
                            <div>
                                <label class="block text-xs uppercase tracking-widest text-slate-400 mb-1">Nombre</label>
                                <input type="text" name="nombre" value="{{ $editNombre }}"
                                    class="w-full rounded-lg border border-slate-600 bg-slate-900/70 px-3 py-2 focus:outline-none focus:ring focus:ring-blue-500"
                                    required>
                                @if ($rowIsEditing && $errors->editarCancha->has('nombre'))
                                    <p class="text-xs text-red-300 mt-1">{{ $errors->editarCancha->first('nombre') }}</p>
                                @endif
                            </div>
                            <div>
                                <label class="block text-xs uppercase tracking-widest text-slate-400 mb-1">Tipo</label>
                                <input type="text" name="tipo" value="{{ $editTipo }}"
                                    class="w-full rounded-lg border border-slate-600 bg-slate-900/70 px-3 py-2 focus:outline-none focus:ring focus:ring-blue-500"
                                    required>
                                @if ($rowIsEditing && $errors->editarCancha->has('tipo'))
                                    <p class="text-xs text-red-300 mt-1">{{ $errors->editarCancha->first('tipo') }}</p>
                                @endif
                            </div>
                            <div class="md:col-span-2">
                                <label class="block text-xs uppercase tracking-widest text-slate-400 mb-1">Descripción</label>
                                <textarea name="descripcion" rows="3"
                                    class="w-full rounded-lg border border-slate-600 bg-slate-900/70 px-3 py-2 focus:outline-none focus:ring focus:ring-blue-500">{{ $editDescripcion }}</textarea>
                                @if ($rowIsEditing && $errors->editarCancha->has('descripcion'))
                                    <p class="text-xs text-red-300 mt-1">{{ $errors->editarCancha->first('descripcion') }}</p>
                                @endif
                            </div>
                            <div>
                                <label class="block text-xs uppercase tracking-widest text-slate-400 mb-1">Precio por hora</label>
                                <input type="number" min="0" step="0.01" name="precio_hora" value="{{ $editPrecio }}"
                                    class="w-full rounded-lg border border-slate-600 bg-slate-900/70 px-3 py-2 focus:outline-none focus:ring focus:ring-blue-500"
                                    required>
                                @if ($rowIsEditing && $errors->editarCancha->has('precio_hora'))
                                    <p class="text-xs text-red-300 mt-1">{{ $errors->editarCancha->first('precio_hora') }}</p>
                                @endif
                            </div>
                            <div>
                                <label class="block text-xs uppercase tracking-widest text-slate-400 mb-1">Imagen actual</label>
                                @if ($cancha->imagen_url)
                                    <img src="{{ asset(ltrim($cancha->imagen_url, '/')) }}" alt="Imagen de {{ $cancha->nombre }}"
                                        class="h-20 w-32 rounded-lg border border-slate-700 object-cover">
                                @else
                                    <p class="text-xs text-slate-400">Sin imagen cargada.</p>
                                @endif
                            </div>
                            <div>
                                <label class="block text-xs uppercase tracking-widest text-slate-400 mb-1">Actualizar imagen</label>
                                <input type="file" name="imagen" accept="image/*"
                                    class="w-full rounded-lg border border-slate-600 bg-slate-900/70 px-3 py-2 text-sm file:bg-slate-800 file:text-slate-200 file:border-0 file:px-4 file:py-2 focus:outline-none focus:ring focus:ring-blue-500">
                                @if ($rowIsEditing && $errors->editarCancha->has('imagen'))
                                    <p class="text-xs text-red-300 mt-1">{{ $errors->editarCancha->first('imagen') }}</p>
                                @endif
                            </div>
                            <div class="flex items-center gap-3">
                                <input type="hidden" name="activa" value="0">
                                <input type="checkbox" id="modal_activa_{{ $cancha->id }}" name="activa" value="1"
                                    class="h-4 w-4 rounded border-slate-500 bg-slate-900 text-blue-500 focus:ring-blue-500"
                                    {{ $editActiva ? 'checked' : '' }}>
                                <label for="modal_activa_{{ $cancha->id }}"
                                    class="text-xs uppercase tracking-widest text-slate-400">Activa para reservas</label>
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
                                    class="px-5 py-2 rounded-lg bg-blue-600 hover:bg-blue-700 text-white font-semibold transition">
                                    Guardar cambios
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        @endforeach

        <div id="feedback-modal"
            class="fixed inset-0 bg-black/60 backdrop-blur-sm hidden items-center justify-center z-50 px-4 py-8"
            data-feedback-modal data-feedback-visible="{{ $feedbackMessage ? 'true' : 'false' }}"
            data-feedback-type="{{ $feedbackType ?? 'success' }}">
            <div
                class="relative w-full max-w-lg border {{ $isErrorFeedback ? 'border-red-500/40' : 'border-emerald-500/40' }} bg-slate-950/95 p-6 shadow-2xl text-white">
                <button type="button" data-feedback-close
                    class="absolute top-3 right-3 text-slate-400 hover:text-white transition" aria-label="Cerrar">
                    ✕
                </button>
                <div class="space-y-4">
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
                            <p class="text-sm uppercase tracking-[0.3em] text-slate-400">{{ $feedbackTitle }}</p>
                            <p class="text-lg font-semibold text-white">{{ $feedbackMessage }}</p>
                        </div>
                    </div>

                    <div class="flex justify-end gap-3">
                        <button type="button" data-feedback-close
                            class="px-5 py-2 rounded-lg font-semibold transition bg-red-500 hover:bg-red-600 text-white {{ $isErrorFeedback ? '' : 'hidden' }}">
                            Cancelar
                        </button>
                        <button type="button" data-feedback-close
                            class="px-5 py-2 rounded-lg font-semibold transition bg-emerald-500 hover:bg-emerald-600 text-slate-900 {{ $isErrorFeedback ? 'hidden' : '' }}">
                            Aceptar
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div id="delete-modal"
            class="fixed inset-0 bg-black/70 backdrop-blur-sm hidden items-center justify-center z-50 px-4 py-8">
            <div class="relative w-full max-w-lg border border-red-500/40 bg-slate-950/95 p-6 shadow-2xl text-white">
                <button type="button" data-delete-cancel
                    class="absolute top-3 right-3 text-slate-400 hover:text-white transition" aria-label="Cerrar">
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

    <!-- SECCIÓN RESERVAS -->
    <section id="reservas" data-section="reservas" class="scroll-mt-32 hidden">
        <!-- ... (todo tu contenido de reservas tal como lo tienes) ... -->
    </section>

    <!-- SECCIÓN BLOQUEOS -->
    <section id="bloqueos" data-section="bloqueos" class="scroll-mt-32 hidden">
        <div class="bg-slate-900 min-h-screen flex justify-center p-10">
            <div class="w-full max-w-5xl space-y-4">
                <div class="flex flex-col gap-2 md:flex-row md:items-center md:justify-between">
                    <div>
                        <h1 class="text-xl font-bold text-white">Bloqueos programados</h1>
                        <p class="text-sm text-slate-400">Controla los horarios donde las canchas no estarán disponibles.</p>
                    </div>
                    <button id="abrirBloqueoModal" type="button"
                        class="flex items-center gap-2 bg-emerald-500 hover:bg-emerald-600 text-slate-950 font-semibold px-4 py-2 rounded-lg shadow-md transition">
                        <span class="text-xl font-bold">+</span>
                        NUEVO BLOQUEO
                    </button>
                </div>

                <div class="overflow-x-auto rounded-2xl border border-slate-800 bg-slate-950/60 shadow-2xl">
                    <table class="w-full text-left text-sm text-gray-300">
                        <thead class="bg-slate-900/80">
                            <tr class="uppercase text-xs text-slate-400 tracking-wide">
                                <th class="px-6 py-3">Cancha</th>
                                <th class="px-6 py-3">Inicio</th>
                                <th class="px-6 py-3">Fin</th>
                                <th class="px-6 py-3">Motivo</th>
                                <th class="px-6 py-3">Registró</th>
                                <th class="px-6 py-3 text-right">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-800">
                            @forelse ($bloqueos as $bloqueo)
                                <tr class="hover:bg-slate-900/70 transition-colors">
                                    <td class="px-6 py-4">
                                        <div class="font-semibold text-white">{{ optional($bloqueo->cancha)->nombre ?? 'Cancha eliminada' }}</div>
                                        <div class="text-xs text-slate-500">ID #{{ $bloqueo->cancha_id }}</div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="font-semibold text-emerald-400">
                                            {{ optional($bloqueo->fecha_inicio)->format('d/m/Y H:i') ?? '—' }}
                                        </div>
                                        <p class="text-xs text-slate-400">Hora local</p>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="font-semibold text-rose-300">
                                            {{ optional($bloqueo->fecha_fin)->format('d/m/Y H:i') ?? '—' }}
                                        </div>
                                        <p class="text-xs text-slate-400">Hora local</p>
                                    </td>
                                    <td class="px-6 py-4 max-w-xs">
                                        <p class="text-sm text-slate-300 leading-snug">
                                            {{ $bloqueo->motivo }}
                                        </p>
                                    </td>
                                    <td class="px-6 py-4">
                                        <p class="font-semibold text-white">
                                            {{ optional($bloqueo->creador)->nombre ?? 'Usuario no disponible' }}
                                        </p>
                                        <p class="text-xs text-slate-400">
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
                                    <td colspan="6" class="px-6 py-10 text-center text-slate-400">
                                        Aún no hay bloqueos registrados.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- MODAL PARA CREAR BLOQUEO -->
        <div id="bloqueo-modal"
            class="fixed inset-0 bg-black/60 backdrop-blur-sm hidden items-center justify-center z-50 px-4 py-8">
            <div
                class="relative w-full max-w-3xl border border-slate-800 bg-slate-950/95 p-8 shadow-2xl max-h-[90vh] overflow-y-auto text-white">
                <button type="button" data-bloqueo-modal-close
                    class="absolute top-4 right-4 bg-emerald-500 text-slate-950 w-10 h-10 rounded-full shadow-lg flex items-center justify-center text-2xl font-bold hover:bg-red-500 hover:text-white transition">
                    ✕
                </button>

                <div class="space-y-6">
                    <div>
                        <p class="text-sm uppercase tracking-[0.3em] text-emerald-300">Nuevo bloqueo</p>
                        <h2 class="text-2xl font-semibold">Bloquear una cancha</h2>
                        <p class="text-slate-400 text-sm">Define el intervalo de fechas y el motivo del bloqueo.</p>
                    </div>

                    <form method="POST" action="{{ route('admin.bloqueos.store') }}" class="grid gap-4 md:grid-cols-2 text-sm">
                        @csrf
                        <div class="md:col-span-2">
                            <label class="block text-xs uppercase tracking-widest text-slate-400 mb-1">Cancha</label>
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
                            <label class="block text-xs uppercase tracking-widest text-slate-400 mb-1">Fecha y hora de inicio</label>
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
                            <label class="block text-xs uppercase tracking-widest text-slate-400 mb-1">Fecha y hora de fin</label>
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
                            <label class="block text-xs uppercase tracking-widest text-slate-400 mb-1">Motivo</label>
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
                                class="px-5 py-2 rounded-lg bg-emerald-500 hover:bg-emerald-600 text-slate-950 font-semibold transition">
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
                class="bloqueo-edit-modal fixed inset-0 bg-black/60 backdrop-blur-sm hidden items-center justify-center z-50 px-4 py-8"
                data-bloqueo-edit-modal data-open-default="{{ $rowIsEditingBloqueo ? 'true' : 'false' }}">
                <div
                    class="relative w-full max-w-3xl border border-slate-800 bg-slate-950/95 p-8 shadow-2xl max-h-[90vh] overflow-y-auto text-white">
                    <button type="button" data-bloqueo-edit-modal-close
                        class="absolute top-4 right-4 bg-emerald-500 text-slate-950 w-10 h-10 rounded-full shadow-lg flex items-center justify-center text-2xl font-bold hover:bg-red-500 hover:text-white transition">
                        ✕
                    </button>

                    <div class="space-y-6">
                        <div>
                            <p class="text-sm uppercase tracking-[0.3em] text-emerald-300">Editar bloqueo</p>
                            <h2 class="text-2xl font-semibold">Actualizar bloqueo de {{ optional($bloqueo->cancha)->nombre ?? 'Cancha eliminada' }}</h2>
                            <p class="text-slate-400 text-sm">Ajusta fechas o cambia el motivo cuando sea necesario.</p>
                        </div>

                        <form method="POST" action="{{ route('admin.bloqueos.update', $bloqueo) }}" class="grid gap-4 md:grid-cols-2 text-sm">
                            @csrf
                            @method('PUT')

                            <div class="md:col-span-2">
                                <label class="block text-xs uppercase tracking-widest text-slate-400 mb-1">Cancha</label>
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
                                <label class="block text-xs uppercase tracking-widest text-slate-400 mb-1">Fecha y hora de inicio</label>
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
                                <label class="block text-xs uppercase tracking-widest text-slate-400 mb-1">Fecha y hora de fin</label>
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
                                <label class="block text-xs uppercase tracking-widest text-slate-400 mb-1">Motivo</label>
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
                                    class="px-5 py-2 rounded-lg bg-emerald-500 hover:bg-emerald-600 text-slate-950 font-semibold transition">
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
                    class="absolute top-3 right-3 text-slate-400 hover:text-white transition" aria-label="Cerrar">
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
                            <p class="text-sm text-slate-400 mt-1">Esta acción no se puede deshacer.</p>
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
        <div class="bg-slate-900 min-h-screen flex justify-center p-10">
            <div class="w-full max-w-5xl space-y-4">
                <div class="flex flex-col gap-2 md:flex-row md:items-center md:justify-between">
                    <div>
                        <h1 class="text-xl font-bold text-white">Historial de precios por cancha</h1>
                        <p class="text-sm text-slate-400">Registra, edita o finaliza periodos de vigencia para cada cancha.</p>
                    </div>
                    <button id="abrirPrecioModal" type="button"
                        class="flex items-center gap-2 bg-amber-500 hover:bg-amber-600 text-slate-950 font-semibold px-4 py-2 rounded-lg shadow-md transition">
                        <span class="text-xl font-bold">+</span>
                        NUEVO PRECIO
                    </button>
                </div>

                <div class="overflow-x-auto rounded-2xl border border-slate-800 bg-slate-950/60 shadow-2xl">
                    <table class="w-full text-left text-sm text-gray-300">
                        <thead class="bg-slate-900/80">
                            <tr class="uppercase text-xs text-slate-400 tracking-wide">
                                <th class="px-6 py-3">Cancha</th>
                                <th class="px-6 py-3 text-right">Precio/Hora</th>
                                <th class="px-6 py-3">Desde</th>
                                <th class="px-6 py-3">Hasta</th>
                                <th class="px-6 py-3 text-center">Estado</th>
                                <th class="px-6 py-3 text-right">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-800">
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
                                <tr class="hover:bg-slate-900/70 transition-colors">
                                    <td class="px-6 py-4">
                                        <div class="font-semibold text-white">{{ optional($precio->cancha)->nombre ?? 'Cancha no disponible' }}</div>
                                        <div class="text-xs text-slate-500">ID #{{ $precio->cancha_id }}</div>
                                    </td>
                                    <td class="px-6 py-4 text-right font-semibold text-amber-300">
                                        ${{ number_format((float) $precio->precio_hora, 2, '.', ',') }}
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="font-semibold text-slate-200">
                                            {{ optional($fechaDesde)->format('d/m/Y H:i') ?? '—' }}
                                        </div>
                                        <p class="text-xs text-slate-400">Hora local</p>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="font-semibold text-slate-200">
                                            {{ optional($fechaHasta)->format('d/m/Y H:i') ?? 'Sin fecha de cierre' }}
                                        </div>
                                        <p class="text-xs text-slate-400">
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
                                    <td colspan="6" class="px-6 py-10 text-center text-slate-400">
                                        Todavía no hay historial de precios registrado.
                                    </td>
                                </tr>
                            @endforelse
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
                        <p class="text-slate-400 text-sm">Establece la cancha, el precio y el periodo de aplicación.</p>
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
                            <label class="block text-xs uppercase tracking-widest text-slate-400 mb-1">Cancha</label>
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
                            <label class="block text-xs uppercase tracking-widest text-slate-400 mb-1">Precio por hora</label>
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
                            <label class="block text-xs uppercase tracking-widest text-slate-400 mb-1">Fecha y hora de inicio</label>
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
                            <label class="block text-xs uppercase tracking-widest text-slate-400 mb-1">Fecha y hora de fin (opcional)</label>
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
                                class="px-5 py-2 rounded-lg bg-amber-500 hover:bg-amber-600 text-slate-950 font-semibold transition">
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
                            <p class="text-slate-400 text-sm">Actualiza el valor o las fechas de vigencia.</p>
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
                                <label class="block text-xs uppercase tracking-widest text-slate-400 mb-1">Cancha</label>
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
                                <label class="block text-xs uppercase tracking-widest text-slate-400 mb-1">Precio por hora</label>
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
                                <label class="block text-xs uppercase tracking-widest text-slate-400 mb-1">Fecha y hora de inicio</label>
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
                                <label class="block text-xs uppercase tracking-widest text-slate-400 mb-1">Fecha y hora de fin (opcional)</label>
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
                                    class="px-5 py-2 rounded-lg bg-amber-500 hover:bg-amber-600 text-slate-950 font-semibold transition">
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
                    class="absolute top-3 right-3 text-slate-400 hover:text-white transition" aria-label="Cerrar">
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
                            <p class="text-sm text-slate-400 mt-1">Las reservas futuras utilizarán el precio vigente restante.</p>
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
</div>

{{-- SCRIPT PARA ACTIVAR SECCIONES Y ESTILOS (TEXTO NEGRO SIEMPRE) --}}
<script>
    (() => {
        const shouldOpenCreateModal = @json($shouldOpenCreateModal);
        const serverEditId = @json($editarCanchaId);
        const shouldOpenBloqueoModal = @json($shouldOpenBloqueoModal);
        const serverBloqueoEditId = @json($editarBloqueoId);
        const shouldOpenPrecioModal = @json($shouldOpenPrecioModal);
        const serverPrecioEditId = @json($editarPrecioId);

        const initNav = () => {
            const navButtons = document.querySelectorAll('#admin-panels-nav [data-section-target]');
            const sections = document.querySelectorAll('[data-section]');

            if (!navButtons.length) {
                return;
            }

            const activeClasses = ['bg-indigo-200', 'shadow'];
            const inactiveClasses = ['bg-white', 'hover:bg-gray-100'];

            const activateSection = (target) => {
                sections.forEach((section) => {
                    section.classList.toggle('hidden', section.dataset.section !== target);
                });

                navButtons.forEach((btn) => {
                    const isActive = btn.dataset.sectionTarget === target;
                    btn.classList.remove(...activeClasses, ...inactiveClasses);
                    btn.classList.add(...(isActive ? activeClasses : inactiveClasses), 'text-gray-900');
                });
            };

            navButtons.forEach((btn) => btn.addEventListener('click', () => activateSection(btn.dataset.sectionTarget)));

            const initialTarget =
                document.querySelector('#admin-panels-nav [data-default]')?.dataset.sectionTarget ||
                navButtons[0]?.dataset.sectionTarget;

            activateSection(initialTarget);
        };

        const initModal = () => {
            const modal = document.getElementById('modal');
            const openButton = document.getElementById('abrirModal');
            if (!modal) {
                return;
            }

            const closeButtons = modal.querySelectorAll('[data-modal-close]');
            const toggleModal = (show) => {
                modal.classList.toggle('hidden', !show);
                modal.classList.toggle('flex', show);
                document.body.classList.toggle('overflow-hidden', show);
            };

            openButton?.addEventListener('click', () => toggleModal(true));
            closeButtons.forEach((btn) => btn.addEventListener('click', () => toggleModal(false)));
            modal.addEventListener('click', (event) => {
                if (event.target === modal) {
                    toggleModal(false);
                }
            });

            if (shouldOpenCreateModal) {
                toggleModal(true);
            }
        };

        const initBloqueoModal = () => {
            const modal = document.getElementById('bloqueo-modal');
            const openButton = document.getElementById('abrirBloqueoModal');
            if (!modal) {
                return;
            }

            const closeButtons = modal.querySelectorAll('[data-bloqueo-modal-close]');
            const toggleModal = (show) => {
                modal.classList.toggle('hidden', !show);
                modal.classList.toggle('flex', show);
                document.body.classList.toggle('overflow-hidden', show);
            };

            openButton?.addEventListener('click', () => toggleModal(true));
            closeButtons.forEach((btn) => btn.addEventListener('click', () => toggleModal(false)));
            modal.addEventListener('click', (event) => {
                if (event.target === modal) {
                    toggleModal(false);
                }
            });

            if (shouldOpenBloqueoModal) {
                toggleModal(true);
            }
        };

        const initPrecioModal = () => {
            const modal = document.getElementById('precio-modal');
            const openButton = document.getElementById('abrirPrecioModal');
            if (!modal) {
                return;
            }

            const closeButtons = modal.querySelectorAll('[data-precio-modal-close]');
            const toggleModal = (show) => {
                modal.classList.toggle('hidden', !show);
                modal.classList.toggle('flex', show);
                document.body.classList.toggle('overflow-hidden', show);
            };

            openButton?.addEventListener('click', () => toggleModal(true));
            closeButtons.forEach((btn) => btn.addEventListener('click', () => toggleModal(false)));
            modal.addEventListener('click', (event) => {
                if (event.target === modal) {
                    toggleModal(false);
                }
            });

            if (shouldOpenPrecioModal) {
                toggleModal(true);
            }
        };

        const initEditModals = () => {
            const modals = document.querySelectorAll('[data-edit-modal]');
            if (!modals.length) {
                return;
            }

            let activeModal = null;
            const setBodyScroll = (locked) => document.body.classList.toggle('overflow-hidden', locked);

            const openModal = (modal) => {
                if (activeModal === modal) {
                    return;
                }
                if (activeModal) {
                    activeModal.classList.add('hidden');
                    activeModal.classList.remove('flex');
                }
                activeModal = modal;
                modal.classList.remove('hidden');
                modal.classList.add('flex');
                setBodyScroll(true);
            };

            const openModalById = (id) => {
                const modal = document.getElementById(id);
                if (modal) {
                    openModal(modal);
                }
            };

            const closeModal = (modal) => {
                if (!modal) {
                    return;
                }
                modal.classList.add('hidden');
                modal.classList.remove('flex');
                if (activeModal === modal) {
                    activeModal = null;
                    setBodyScroll(false);
                }
            };

            document.querySelectorAll('[data-edit-target]').forEach((btn) => {
                btn.addEventListener('click', () => openModalById(btn.dataset.editTarget));
            });

            document.querySelectorAll('[data-edit-modal-close]').forEach((btn) => {
                btn.addEventListener('click', () => closeModal(btn.closest('[data-edit-modal]')));
            });

            modals.forEach((modal) => {
                modal.addEventListener('click', (event) => {
                    if (event.target === modal) {
                        closeModal(modal);
                    }
                });

                if (modal.dataset.openDefault === 'true') {
                    openModal(modal);
                }
            });

            if (!activeModal && serverEditId) {
                openModalById(`edit-modal-${serverEditId}`);
            }
        };

        const initBloqueoEditModals = () => {
            const modals = document.querySelectorAll('[data-bloqueo-edit-modal]');
            if (!modals.length) {
                return;
            }

            let activeModal = null;
            const setBodyScroll = (locked) => document.body.classList.toggle('overflow-hidden', locked);

            const openModal = (modal) => {
                if (activeModal === modal) {
                    return;
                }
                if (activeModal) {
                    activeModal.classList.add('hidden');
                    activeModal.classList.remove('flex');
                }
                activeModal = modal;
                modal.classList.remove('hidden');
                modal.classList.add('flex');
                setBodyScroll(true);
            };

            const openModalById = (id) => {
                const modal = document.getElementById(id);
                if (modal) {
                    openModal(modal);
                }
            };

            const closeModal = (modal) => {
                if (!modal) {
                    return;
                }
                modal.classList.add('hidden');
                modal.classList.remove('flex');
                if (activeModal === modal) {
                    activeModal = null;
                    setBodyScroll(false);
                }
            };

            document.querySelectorAll('[data-bloqueo-edit-target]').forEach((btn) => {
                btn.addEventListener('click', () => openModalById(btn.dataset.bloqueoEditTarget));
            });

            document.querySelectorAll('[data-bloqueo-edit-modal-close]').forEach((btn) => {
                btn.addEventListener('click', () => closeModal(btn.closest('[data-bloqueo-edit-modal]')));
            });

            modals.forEach((modal) => {
                modal.addEventListener('click', (event) => {
                    if (event.target === modal) {
                        closeModal(modal);
                    }
                });

                if (modal.dataset.openDefault === 'true') {
                    openModal(modal);
                }
            });

            if (!activeModal && serverBloqueoEditId) {
                openModalById(`bloqueo-edit-modal-${serverBloqueoEditId}`);
            }
        };

        const initPrecioEditModals = () => {
            const modals = document.querySelectorAll('[data-precio-edit-modal]');
            if (!modals.length) {
                return;
            }

            let activeModal = null;
            const setBodyScroll = (locked) => document.body.classList.toggle('overflow-hidden', locked);

            const openModal = (modal) => {
                if (activeModal === modal) {
                    return;
                }
                if (activeModal) {
                    activeModal.classList.add('hidden');
                    activeModal.classList.remove('flex');
                }
                activeModal = modal;
                modal.classList.remove('hidden');
                modal.classList.add('flex');
                setBodyScroll(true);
            };

            const openModalById = (id) => {
                const modal = document.getElementById(id);
                if (modal) {
                    openModal(modal);
                }
            };

            const closeModal = (modal) => {
                if (!modal) {
                    return;
                }
                modal.classList.add('hidden');
                modal.classList.remove('flex');
                if (activeModal === modal) {
                    activeModal = null;
                    setBodyScroll(false);
                }
            };

            document.querySelectorAll('[data-precio-edit-target]').forEach((btn) => {
                btn.addEventListener('click', () => openModalById(btn.dataset.precioEditTarget));
            });

            document.querySelectorAll('[data-precio-edit-modal-close]').forEach((btn) => {
                btn.addEventListener('click', () => closeModal(btn.closest('[data-precio-edit-modal]')));
            });

            modals.forEach((modal) => {
                modal.addEventListener('click', (event) => {
                    if (event.target === modal) {
                        closeModal(modal);
                    }
                });

                if (modal.dataset.openDefault === 'true') {
                    openModal(modal);
                }
            });

            if (!activeModal && serverPrecioEditId) {
                openModalById(`precio-edit-modal-${serverPrecioEditId}`);
            }
        };

        const initFeedbackModal = () => {
            const feedbackModal = document.getElementById('feedback-modal');
            if (!feedbackModal) {
                return;
            }

            const shouldShow = feedbackModal.dataset.feedbackVisible === 'true';
            const toggleModal = (show) => {
                feedbackModal.classList.toggle('hidden', !show);
                feedbackModal.classList.toggle('flex', show);
                document.body.classList.toggle('overflow-hidden', show);
            };

            feedbackModal.querySelectorAll('[data-feedback-close]').forEach((btn) => {
                btn.addEventListener('click', () => toggleModal(false));
            });

            feedbackModal.addEventListener('click', (event) => {
                if (event.target === feedbackModal) {
                    toggleModal(false);
                }
            });

            if (shouldShow) {
                toggleModal(true);
            }
        };

        const initDeleteModal = () => {
            const deleteModal = document.getElementById('delete-modal');
            if (!deleteModal) {
                return;
            }

            let pendingForm = null;
            const toggleModal = (show) => {
                deleteModal.classList.toggle('hidden', !show);
                deleteModal.classList.toggle('flex', show);
                document.body.classList.toggle('overflow-hidden', show);
                if (!show) {
                    pendingForm = null;
                }
            };

            document.querySelectorAll('[data-delete-target]').forEach((btn) => {
                btn.addEventListener('click', () => {
                    pendingForm = document.getElementById(btn.dataset.deleteTarget);
                    toggleModal(true);
                });
            });

            deleteModal.querySelectorAll('[data-delete-cancel]').forEach((btn) => {
                btn.addEventListener('click', () => toggleModal(false));
            });

            const confirmBtn = deleteModal.querySelector('[data-delete-confirm]');
            confirmBtn?.addEventListener('click', () => {
                if (pendingForm) {
                    pendingForm.submit();
                }
            });

            deleteModal.addEventListener('click', (event) => {
                if (event.target === deleteModal) {
                    toggleModal(false);
                }
            });
        };

        const initBloqueoDeleteModal = () => {
            const deleteModal = document.getElementById('bloqueo-delete-modal');
            if (!deleteModal) {
                return;
            }

            let pendingForm = null;
            const toggleModal = (show) => {
                deleteModal.classList.toggle('hidden', !show);
                deleteModal.classList.toggle('flex', show);
                document.body.classList.toggle('overflow-hidden', show);
                if (!show) {
                    pendingForm = null;
                }
            };

            document.querySelectorAll('[data-bloqueo-delete-target]').forEach((btn) => {
                btn.addEventListener('click', () => {
                    pendingForm = document.getElementById(btn.dataset.bloqueoDeleteTarget);
                    toggleModal(true);
                });
            });

            deleteModal.querySelectorAll('[data-bloqueo-delete-cancel]').forEach((btn) => {
                btn.addEventListener('click', () => toggleModal(false));
            });

            const confirmBtn = deleteModal.querySelector('[data-bloqueo-delete-confirm]');
            confirmBtn?.addEventListener('click', () => {
                if (pendingForm) {
                    pendingForm.submit();
                }
            });

            deleteModal.addEventListener('click', (event) => {
                if (event.target === deleteModal) {
                    toggleModal(false);
                }
            });
        };

        const initPrecioDeleteModal = () => {
            const deleteModal = document.getElementById('precio-delete-modal');
            if (!deleteModal) {
                return;
            }

            let pendingForm = null;
            const toggleModal = (show) => {
                deleteModal.classList.toggle('hidden', !show);
                deleteModal.classList.toggle('flex', show);
                document.body.classList.toggle('overflow-hidden', show);
                if (!show) {
                    pendingForm = null;
                }
            };

            document.querySelectorAll('[data-precio-delete-target]').forEach((btn) => {
                btn.addEventListener('click', () => {
                    pendingForm = document.getElementById(btn.dataset.precioDeleteTarget);
                    toggleModal(true);
                });
            });

            deleteModal.querySelectorAll('[data-precio-delete-cancel]').forEach((btn) => {
                btn.addEventListener('click', () => toggleModal(false));
            });

            const confirmBtn = deleteModal.querySelector('[data-precio-delete-confirm]');
            confirmBtn?.addEventListener('click', () => {
                if (pendingForm) {
                    pendingForm.submit();
                }
            });

            deleteModal.addEventListener('click', (event) => {
                if (event.target === deleteModal) {
                    toggleModal(false);
                }
            });
        };

        const initPage = () => {
            initNav();
            initModal();
            initBloqueoModal();
            initPrecioModal();
            initEditModals();
            initBloqueoEditModals();
            initPrecioEditModals();
            initFeedbackModal();
            initDeleteModal();
            initBloqueoDeleteModal();
            initPrecioDeleteModal();
        };

        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', initPage, { once: true });
        } else {
            initPage();
        }
    })();
</script>

@endsection
