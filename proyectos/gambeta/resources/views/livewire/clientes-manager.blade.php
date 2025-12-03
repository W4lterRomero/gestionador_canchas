<div class="space-y-6">
    <!-- MENSAJES -->
    @if (session()->has('message'))
        <div class="bg-green-50 border-l-4 border-green-500 text-green-800 p-4 rounded-lg shadow">
            {{ session('message') }}
        </div>
    @endif

    @if (session()->has('error'))
        <div class="bg-red-50 border-l-4 border-red-500 text-red-800 p-4 rounded-lg shadow">
            {{ session('error') }}
        </div>
    @endif

    <div class="bg-white/90 backdrop-blur rounded-xl shadow-lg p-4 md:p-6">
        <div class="flex flex-col lg:flex-row gap-4">
            <!-- Búsqueda -->
            <div class="flex-1">
                <label class="block text-sm font-medium text-gray-700 mb-2">Buscar</label>
                <input type="text"
                       wire:model.live.debounce.300ms="search"
                       placeholder="Nombre, teléfono, email..."
                       class="w-full px-4 py-2 bg-white border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
            </div>

            <!-- Filtro -->
            <div class="w-full lg:w-48">
                <label class="block text-sm font-medium text-gray-700 mb-2">Filtrar</label>
                <select wire:model.live="filtro"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent bg-white">
                    <option value="todos">Todos</option>
                    <option value="frecuentes">Frecuentes</option>
                    <option value="normales">Normales</option>
                </select>
            </div>

            <!-- Ordenar -->
            <div class="w-full lg:w-52">
                <label class="block text-sm font-medium text-gray-700 mb-2">Ordenar por</label>
                <select wire:model.live="ordenarPor"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent bg-white">
                    <option value="nombre">Nombre</option>
                    <option value="total_reservas">Total Reservas</option>
                    <option value="ultima_reserva">Última Reserva</option>
                </select>
            </div>

            <!-- Botón -->
            <div class="w-full lg:w-auto flex items-end">
                <button wire:click="crearCliente"
                        class="w-full lg:w-auto px-6 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition font-medium shadow-md">
                    + Nuevo Cliente
                </button>
            </div>
        </div>
    </div>

    <div class="hidden md:block bg-white/90 backdrop-blur rounded-xl shadow-lg overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Cliente</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Contacto</th>
                        <th class="px-6 py-3 text-center text-xs font-semibold text-gray-700 uppercase">Estado</th>
                        <th class="px-6 py-3 text-center text-xs font-semibold text-gray-700 uppercase">Reservas</th>
                        <th class="px-6 py-3 text-center text-xs font-semibold text-gray-700 uppercase">Descuento</th>
                        <th class="px-6 py-3 text-center text-xs font-semibold text-gray-700 uppercase">Acciones</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-100">
                    @forelse ($clientes as $cliente)
                        <tr class="hover:bg-gray-50 transition">
                            <!-- Cliente -->
                            <td class="px-6 py-4">
                                <div>
                                    <p class="font-semibold text-gray-900">{{ $cliente->nombre }}</p>
                                    @if($cliente->equipo)
                                        <p class="text-sm text-gray-600">{{ $cliente->equipo }}</p>
                                    @endif
                                </div>
                            </td>

                            <!-- Contacto -->
                            <td class="px-6 py-4">
                                <p class="text-sm text-gray-900">{{ $cliente->telefono }}</p>
                                @if($cliente->email)
                                    <p class="text-sm text-gray-600">{{ $cliente->email }}</p>
                                @endif
                            </td>

                            <!-- Estado -->
                            <td class="px-6 py-4 text-center">
                                @if($cliente->es_frecuente)
                                    <span class="px-3 py-1 inline-flex text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                        ⭐ Frecuente
                                    </span>
                                @else
                                    <span class="px-3 py-1 inline-flex text-xs font-semibold rounded-full bg-gray-100 text-gray-700">
                                        Normal
                                    </span>
                                @endif
                            </td>

                            <!-- Reservas -->
                            <td class="px-6 py-4 text-center">
                                <p class="text-sm font-semibold text-gray-900">{{ $cliente->total_reservas }}</p>
                                @if($cliente->ultima_reserva)
                                    <p class="text-xs text-gray-500">{{ \Carbon\Carbon::parse($cliente->ultima_reserva)->format('d/m/Y') }}</p>
                                @endif
                            </td>

                            <!-- Descuento -->
                            <td class="px-6 py-4 text-center">
                                @if($cliente->descuento_porcentaje > 0)
                                    <span class="text-sm font-bold text-green-600">{{ $cliente->descuento_porcentaje }}%</span>
                                @else
                                    <span class="text-gray-400">-</span>
                                @endif
                            </td>

                            <!-- Acciones -->
                            <td class="px-6 py-4 text-center">
                                <div class="flex justify-center gap-2">
                                    <button wire:click="editarCliente({{ $cliente->id }})"
                                            class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition"
                                            title="Editar">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                    </button>

                                    <button wire:click="toggleFrecuente({{ $cliente->id }})"
                                            class="p-2 {{ $cliente->es_frecuente ? 'text-yellow-500 hover:bg-yellow-50' : 'text-gray-400 hover:bg-gray-50' }} rounded-lg transition"
                                            title="{{ $cliente->es_frecuente ? 'Desmarcar' : 'Marcar' }} frecuente">
                                        <svg class="w-5 h-5" fill="{{ $cliente->es_frecuente ? 'currentColor' : 'none' }}" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
                                        </svg>
                                    </button>

                                    @if(auth()->user()->isAdmin())
                                        <button wire:click="confirmarEliminar({{ $cliente->id }})"
                                                class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition"
                                                title="Eliminar">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                            </svg>
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                                No se encontraron clientes
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- PAGINACIÓN -->
        <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
            {{ $clientes->links() }}
        </div>
    </div>

    <div class="md:hidden space-y-4">
        @forelse ($clientes as $cliente)
            <div class="bg-white/90 backdrop-blur rounded-xl shadow-lg p-4">
                <!-- Header -->
                <div class="flex justify-between items-start mb-3">
                    <div class="flex-1">
                        <h3 class="font-semibold text-gray-900">{{ $cliente->nombre }}</h3>
                        @if($cliente->equipo)
                            <p class="text-sm text-gray-600">{{ $cliente->equipo }}</p>
                        @endif
                    </div>
                    @if($cliente->es_frecuente)
                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">
                            ⭐ Frecuente
                        </span>
                    @endif
                </div>

                <!-- Info -->
                <div class="space-y-2 mb-3 text-sm">
                    <p class="text-gray-700"><span class="font-medium">Tel:</span> {{ $cliente->telefono }}</p>
                    @if($cliente->email)
                        <p class="text-gray-700"><span class="font-medium">Email:</span> {{ $cliente->email }}</p>
                    @endif
                    <div class="flex justify-between">
                        <span class="text-gray-700"><span class="font-medium">Reservas:</span> {{ $cliente->total_reservas }}</span>
                        @if($cliente->descuento_porcentaje > 0)
                            <span class="text-green-600 font-bold">{{ $cliente->descuento_porcentaje }}% descuento</span>
                        @endif
                    </div>
                </div>

                <!-- Acciones -->
                <div class="flex gap-2 pt-3 border-t border-gray-200">
                    <button wire:click="editarCliente({{ $cliente->id }})"
                            class="flex-1 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition text-sm font-medium">
                        Editar
                    </button>
                    <button wire:click="toggleFrecuente({{ $cliente->id }})"
                            class="px-4 py-2 {{ $cliente->es_frecuente ? 'bg-yellow-500 hover:bg-yellow-600' : 'bg-gray-400 hover:bg-gray-500' }} text-white rounded-lg transition">
                        ⭐
                    </button>
                    @if(auth()->user()->isAdmin())
                        <button wire:click="confirmarEliminar({{ $cliente->id }})"
                                class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition">
                            🗑️
                        </button>
                    @endif
                </div>
            </div>
        @empty
            <div class="bg-white/90 backdrop-blur rounded-xl shadow-lg p-8 text-center text-gray-500">
                No se encontraron clientes
            </div>
        @endforelse

        <!-- PAGINACIÓN MOBILE -->
        <div class="bg-white/90 backdrop-blur rounded-xl shadow-lg p-4">
            {{ $clientes->links() }}
        </div>
    </div>

    <!-- MODAL CREAR/EDITAR -->
    @if($modalAbierto)
        <div class="fixed inset-0 z-50 overflow-y-auto">
            <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20">
                <div class="fixed inset-0 bg-black/50 backdrop-blur-sm transition-opacity" wire:click="cerrarModal"></div>

                <div class="relative bg-white rounded-xl shadow-2xl max-w-2xl w-full max-h-[90vh] overflow-y-auto z-10">
                    <!-- Header -->
                    <div class="sticky top-0 bg-green-600 px-6 py-4 rounded-t-xl z-20">
                        <h3 class="text-xl font-semibold text-white">
                            {{ $esEdicion ? 'Editar Cliente' : 'Nuevo Cliente' }}
                        </h3>
                    </div>

                    <!-- Body -->
                    <div class="px-6 py-4 bg-white">
                        <form wire:submit.prevent="guardarCliente">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <!-- Nombre -->
                                <div class="md:col-span-2">
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Nombre *</label>
                                    <input type="text"
                                           wire:model="nombre"
                                           class="w-full px-4 py-2 bg-white border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                                           placeholder="Nombre completo">
                                    @error('nombre') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                </div>

                                <!-- Teléfono -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Teléfono *</label>
                                    <input type="text"
                                           wire:model="telefono"
                                           class="w-full px-4 py-2 bg-white border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                                           placeholder="0000-0000">
                                    @error('telefono') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                </div>

                                <!-- Email -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                                    <input type="email"
                                           wire:model="email"
                                           class="w-full px-4 py-2 bg-white border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                                           placeholder="correo@ejemplo.com">
                                    @error('email') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                </div>

                                <!-- Equipo -->
                                <div class="md:col-span-2">
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Equipo/Grupo</label>
                                    <input type="text"
                                           wire:model="equipo"
                                           class="w-full px-4 py-2 bg-white border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                                           placeholder="Nombre del equipo">
                                    @error('equipo') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                </div>

                                <div>
                                    <label class="flex items-center space-x-2 mt-2">
                                        <input type="checkbox"
                                               wire:model="es_frecuente"
                                               class="rounded border-gray-300 bg-white text-green-600 focus:ring-green-500 w-5 h-5">
                                        <span class="text-sm font-medium text-gray-700">⭐ Cliente Frecuente</span>
                                    </label>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Descuento (%)</label>
                                    <input type="number"
                                           wire:model="descuento_porcentaje"
                                           step="0.01"
                                           min="0"
                                           max="100"
                                           class="w-full px-4 py-2 bg-white border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                                           placeholder="0.00">
                                    @error('descuento_porcentaje') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                </div>

                                <!-- Notas -->
                                <div class="md:col-span-2">
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Notas</label>
                                    <textarea wire:model="notas"
                                              rows="3"
                                              class="w-full px-4 py-2 bg-white border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent resize-none"
                                              placeholder="Notas adicionales..."></textarea>
                                    @error('notas') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            <!-- Botones -->
                            <div class="flex flex-col sm:flex-row justify-end gap-3 mt-6 pb-4">
                                <button type="button"
                                        wire:click="cerrarModal"
                                        class="px-6 py-2 border-2 border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 font-medium transition">
                                    Cancelar
                                </button>
                                <button type="submit"
                                        class="px-6 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 font-medium shadow-md transition">
                                    Guardar
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- MODAL ELIMINAR -->
    @if($modalEliminar)
        <div class="fixed inset-0 z-50 overflow-y-auto">
            <div class="flex items-center justify-center min-h-screen px-4">
                <div class="fixed inset-0 bg-black/50 backdrop-blur-sm transition-opacity" wire:click="$set('modalEliminar', false)"></div>

                <div class="relative bg-white rounded-xl shadow-2xl max-w-md w-full p-6">
                    <div class="text-center mb-4">
                        <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100 mb-4">
                            <svg class="h-6 w-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">¿Eliminar cliente?</h3>
                        <p class="text-sm text-gray-600">Esta acción no se puede deshacer.</p>
                    </div>

                    <div class="flex flex-col sm:flex-row gap-3">
                        <button wire:click="$set('modalEliminar', false)"
                                class="flex-1 px-4 py-2 border-2 border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 font-medium transition">
                            Cancelar
                        </button>
                        <button wire:click="eliminarCliente"
                                class="flex-1 px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 font-medium shadow-md transition">
                            Eliminar
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
