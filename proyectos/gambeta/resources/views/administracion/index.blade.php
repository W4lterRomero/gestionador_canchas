@extends('components.layouts.app')

@php
    $canchas = $canchas ?? collect();
    $editarCanchaId = session('editarCanchaId');
    $shouldOpenCreateModal = $errors->crearCancha->any();
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
                @if (session('status'))
                    <div class="rounded-xl border border-emerald-500/50 bg-emerald-500/10 px-5 py-3 text-sm text-emerald-200">
                        {{ session('status') }}
                    </div>
                @endif

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
                                            <form method="POST" action="{{ route('admin.canchas.destroy', $cancha) }}"
                                                onsubmit="return confirm('¿Eliminar la cancha {{ $cancha->nombre }}?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="hover:text-red-400 transition-colors" title="Eliminar">
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
    </section>

    <!-- SECCIÓN RESERVAS -->
    <section id="reservas" data-section="reservas" class="scroll-mt-32 hidden">
        <!-- ... (todo tu contenido de reservas tal como lo tienes) ... -->
    </section>

    <!-- SECCIÓN BLOQUEOS -->
    <section id="bloqueos" data-section="bloqueos" class="scroll-mt-32 hidden">
        <!-- ... (todo tu contenido de bloqueos tal como lo tienes) ... -->
    </section>

    <!-- SECCIÓN PRECIOS -->
    <section id="precios" data-section="precios" class="scroll-mt-32 hidden">
        <!-- ... (todo tu contenido de precios tal como lo tienes) ... -->
    </section>
</div>

{{-- SCRIPT PARA ACTIVAR SECCIONES Y ESTILOS (TEXTO NEGRO SIEMPRE) --}}
<script>
    (() => {
        const shouldOpenCreateModal = @json($shouldOpenCreateModal);
        const serverEditId = @json($editarCanchaId);

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

        const initPage = () => {
            initNav();
            initModal();
            initEditModals();
        };

        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', initPage, { once: true });
        } else {
            initPage();
        }
    })();
</script>

@endsection
