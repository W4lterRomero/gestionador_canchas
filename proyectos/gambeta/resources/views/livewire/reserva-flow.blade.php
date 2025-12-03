<div>
<section class="container mx-auto px-6 py-10">

    <div class="w-full mb-10 select-none">

        <div class="flex items-center justify-between relative">

            <div class="absolute top-1/2 left-0 w-full border-t border-green-500"></div>

            @foreach ([1 => 'RESERVA', 2 => 'CLIENTE', 3 => 'PAGO', 4 => 'DETALLES FINALES'] as $num => $titulo)

                <div wire:click="irPaso({{ $num }})"
                     class="relative z-10 text-center cursor-pointer">

                    <p class="text-sm font-semibold
                        {{ $pasoActual == $num ? 'text-green-700' : 'text-gray-400' }}">
                        Paso {{ $num }}
                    </p>

                    <div class="
                        w-12 h-12 rounded-full flex items-center justify-center mx-auto text-lg font-bold border transition
                        {{ $pasoActual == $num
                            ? 'bg-green-600 text-white border-green-700 shadow-xl'
                            : 'bg-white text-gray-600 border-gray-400 hover:border-green-600 hover:text-green-600'
                        }}
                    ">
                        {{ $num }}
                    </div>

                    <p class="mt-2 text-xs tracking-wide font-semibold
                        {{ $pasoActual == $num ? 'text-green-700' : 'text-gray-500' }}">
                        {{ $titulo }}
                    </p>

                </div>

            @endforeach

        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-10">

        <div class="md:col-span-2 bg-white rounded-2xl shadow-xl p-8 space-y-10 border border-green-200">

    @if ($pasoActual == 1)
    <div class="space-y-6">

        @if ($errors->any())
            <div class="p-3 bg-red-100 border border-red-400 text-red-700 rounded-lg">
                Debes completar los campos requeridos antes continuar.
            </div>
        @endif

        <h2 class="text-2xl font-bold text-green-700">Reservar cancha</h2>

        <div>
            <label class="font-semibold text-gray-700">Cancha</label>

            <select
                wire:model.live="cancha"
                class="w-full bg-gray-100 rounded-xl px-4 py-2
                    {{ $errors->has('cancha') ? 'border-red-500' : 'border-green-600' }}">
                <option value="">-- Seleccionar --</option>

                @foreach($canchas as $c)
                    <option value="{{ $c->id }}">{{ $c->nombre }}</option>
                @endforeach
            </select>

            @error('cancha')
                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        @if ($canchaObj)
        <div class="flex gap-4 bg-green-50 border border-green-200 rounded-xl p-4 shadow">

            <img src="{{ $canchaImagen }}"
                class="w-32 h-24 rounded-xl object-cover">

            <div class="flex flex-col justify-center">
                <p class="text-xl font-bold text-green-700">{{ $canchaTitulo }}</p>
                <p class="text-gray-600 text-sm">{{ $canchaObj->tipo }}</p>

                <p class="text-gray-600 text-sm">
                    Precio por hora:
                    <b class="text-green-700">${{ number_format($precioHora, 2) }}</b>
                </p>
            </div>

        </div>
        @endif

        @if($canchaObj)
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

            <div class="col-span-1">
                @livewire('calendario-reserva', [
                    'fechaInicial' => $fecha,
                    'canchaId'     => $cancha
                ])
            </div>

            <div class="col-span-1 space-y-4">

                <div>
                    <label class="font-semibold text-gray-700">Duración (horas)</label>

                    <input type="number"
                        min="1"
                        wire:model.live="duracion"
                        class="w-full bg-gray-100 rounded-xl px-4 py-2
                            {{ $errors->has('duracion') ? 'border-red-500' : 'border-green-600' }}">

                    @error('duracion')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="font-semibold text-gray-700">Precio por hora</label>

                    <input type="text"
                        wire:model="precioHora"
                        class="w-full bg-gray-100 rounded-xl px-4 py-2 text-gray-600
                            {{ $errors->has('precioHora') ? 'border-red-500' : 'border-green-600' }}"
                        readonly>

                    @error('precioHora')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- TOTAL -->
                <div class="p-4 bg-green-100 rounded-xl border border-green-300">
                    <p class="text-gray-700 font-semibold">Total:</p>
                    <p class="text-3xl font-bold text-green-700">
                        ${{ number_format($this->total, 2) }}
                    </p>
                </div>

            </div>

        </div>
        @endif

        <div class="flex justify-end">
            <button wire:click="siguiente"
                class="bg-green-600 hover:bg-green-500 text-white px-8 py-2 rounded-full font-bold shadow">
                Continuar
            </button>
        </div>

    </div>
    @endif

@if ($pasoActual == 2)
<div class="space-y-6">

    <h2 class="text-2xl font-bold text-green-700">Datos del cliente</h2>

    <div class="relative">

        <i class="fa-solid fa-magnifying-glass text-green-700 absolute left-3 top-3"></i>

        <input type="text"
            wire:model.live="busqueda"
            placeholder="Buscar cliente por nombre o teléfono"
            class="w-full bg-gray-100 border border-green-600 rounded-xl pl-10 pr-4 py-2">

        @if(!empty($resultados))
            <div class="absolute w-full bg-white border border-green-300 rounded-xl mt-1 shadow-xl z-20">

                @foreach ($resultados as $r)
                    <div wire:click="seleccionarCliente({{ $r->id }})"
                        class="px-4 py-2 cursor-pointer hover:bg-green-100">

                        <p class="font-semibold text-green-700">{{ $r->nombre }}</p>
                        <p class="text-gray-600 text-sm">{{ $r->telefono }}</p>

                    </div>
                @endforeach

            </div>
        @endif

    </div>

    <!-- INPUTS DE CLIENTE -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6">

    <input type="text" wire:model.live="nombre"
        class="bg-gray-100 border border-green-600 rounded-xl px-4 py-2"
        placeholder="Nombre completo"
        @disabled($clienteExiste)>

    <input type="text" wire:model.live="telefono"
        class="bg-gray-100 border border-green-600 rounded-xl px-4 py-2"
        placeholder="0000-0000"
        @disabled($clienteExiste)>

    <input type="text" wire:model.live="equipo"
        class="bg-gray-100 border border-green-600 rounded-xl px-4 py-2"
        placeholder="Equipo o grupo (opcional)"
        @disabled($clienteExiste)>

</div>

<textarea wire:model.live="notas"
    class="bg-gray-100 border border-green-600 rounded-xl px-4 py-2"
    placeholder="Notas del cliente"
    @disabled($clienteExiste)></textarea>
</div>

@endif



@if ($pasoActual == 3)
<div class="space-y-6">

    @if ($errors->any())
        <div class="p-3 bg-red-100 border border-red-400 text-red-700 rounded-lg">
            Debes completar los campos requeridos antes continuar.
        </div>
    @endif

    <h2 class="text-2xl font-bold text-green-700">Pago del cliente</h2>

    <div class="space-y-4">

        {{-- ESTADO DEL PAGO --}}
        <div>
            <label class="font-semibold text-gray-700">Estado del pago</label>
            <select wire:model="estadoPago"
                wire:change="actualizarEstadoPago"
                class="w-full bg-gray-100 rounded-xl px-4 py-2
                    {{ $errors->has('estadoPago') ? 'border-red-500' : 'border-green-600' }}">
                <option value="">-- Seleccionar --</option>
                <option value="pagado">Pagado completo</option>
                <option value="adelanto">Dejó adelanto</option>
                <option value="nopago">No pagó</option>
            </select>

            @error('estadoPago')
                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- TOTAL A PAGAR --}}
        <div>
            <label class="font-semibold text-gray-700">Total a pagar</label>
        <input type="text"
            wire:model="totalPagar"
            wire:input="validarTotalPagar"

            @if($estadoPago === 'pagado' || $estadoPago === 'nopago')
                disabled
            @endif

            class="w-full bg-gray-100 rounded-xl px-4 py-2"
                    {{ $errors->has('totalPagar') ? 'border-red-500' : 'border-green-600' }}"
            >


            @error('totalPagar')
                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- METODO DE PAGO --}}
        <div>
            <label class="font-semibold text-gray-700">Método de pago</label>
            <select wire:model="metodoPago"
                class="w-full bg-gray-100 rounded-xl px-4 py-2
                    {{ $errors->has('metodoPago') ? 'border-red-500' : 'border-green-600' }}">
                <option value="">-- Seleccionar --</option>
                <option value="efectivo">Efectivo</option>
                <option value="tarjeta">Tarjeta</option>
                <option value="transferencia">Transferencia</option>
            </select>

            @error('metodoPago')
                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="text-center pt-4">
            <button wire:click="generarComprobante"
                class="bg-green-600 hover:bg-green-500 text-white px-8 py-2 rounded-full font-bold shadow">
                Generar comprobante PDF
            </button>
        </div>

        @if ($comprobantePdf)
        <div class="mt-6 p-4 bg-green-50 border border-green-300 rounded-xl text-center">
            <p class="font-semibold text-green-700">Comprobante generado correctamente</p>

            <a href="{{ asset($comprobantePdf) }}" download
                class="mt-3 inline-block bg-green-600 hover:bg-green-500 text-white px-6 py-2 rounded-full font-bold shadow">
                Descargar comprobante
            </a>
        </div>
        @endif

    </div>

    <div class="flex justify-between mt-6">
        <button wire:click="anterior" class="text-green-600 font-bold">← Volver</button>
        <button wire:click="siguiente"
            class="bg-green-600 hover:bg-green-500 text-white px-8 py-2 rounded-full font-bold shadow">
            Continuar
        </button>
    </div>

</div>
@endif


    @if ($pasoActual == 4)
    <div class="space-y-8">

        <h2 class="text-3xl font-bold text-green-700 mb-6">
            Detalles de la reserva
        </h2>

        {{-- ======================= --}}
        {{-- SECCIÓN CANCHA --}}
        {{-- ======================= --}}
        <div class="space-y-2">
            <h3 class="text-xl font-bold text-green-600">Detalles de la cancha</h3>

            <p><b>Cancha:</b> {{ $canchaObj->nombre }}</p>

            <p><b>Fecha y hora:</b> {{ $fecha }} — {{ $hora }}</p>

            <p><b>Duración:</b> {{ $duracion }} h</p>
        </div>

        {{-- ======================= --}}
        {{-- SECCIÓN CLIENTE --}}
        {{-- ======================= --}}
        <div class="space-y-2 pt-4">
            <h3 class="text-xl font-bold text-green-600">Detalles del cliente</h3>

            <p><b>Cliente:</b> {{ $nombre }} — {{ $telefono }}</p>

            <p><b>Email:</b> {{ $email }}</p>

            <p><b>Equipo:</b> {{ $equipo ?: '—' }}</p>
        </div>

        {{-- ======================= --}}
        {{-- SECCIÓN PAGO Y ESTADO --}}
        {{-- ======================= --}}
        <div class="space-y-2 pt-4">
            <h3 class="text-xl font-bold text-green-600">Detalles del pago y estado</h3>

            <p><b>Estado de la reserva:</b> {{ $estadoReserva }}</p>

            <p><b>Estado del pago:</b> {{ $estadoPago }}</p>

            <p><b>Método de pago:</b> {{ $metodoPago }}</p>

            <p><b>Observaciones:</b> {{ $observaciones ?: 'Sin observaciones' }}</p>

            <p class="text-xl font-bold pt-2">
                Total: ${{ number_format($this->total, 2) }}
            </p>
        </div>

        <div class="flex justify-end pt-6">
            <button wire:click="abrirConfirmacion"
                class="bg-green-700 hover:bg-green-600 text-white px-8 py-2 rounded-full font-bold shadow">
                Finalizar reserva
            </button>
        </div>
    </div>
    @endif

        </div>

<div class="md:col-span-1">

    <div class="bg-gray-900 text-white rounded-2xl shadow-xl p-5 border border-green-500/30">

        <h2 class="text-xl font-bold mb-3 flex items-center gap-2">
            <i class="fa-solid fa-cart-shopping text-green-400"></i>
            Resumen
        </h2>

        <div class="grid grid-cols-2 gap-3 text-sm">

            <div>
                <p class="text-green-400 font-semibold">Cancha</p>
                <p class="text-gray-200">{{ $canchaTitulo ?? '—' }}</p>
            </div>

            <div>
                <p class="text-green-400 font-semibold">Fecha</p>
                <p class="text-gray-200">{{ $fecha ?? '—' }}</p>
            </div>

            <div>
                <p class="text-green-400 font-semibold">Hora</p>
                <p class="text-gray-200">{{ $hora ?? '—' }}</p>
            </div>

            <div>
                <p class="text-green-400 font-semibold">Duración</p>
                <p class="text-gray-200">{{ $duracion }} h</p>
            </div>

            <div>
                <p class="text-green-400 font-semibold">Estado reserva</p>
                <p class="text-gray-200">{{ $estadoPago ?: '—' }}</p>
            </div>

            <div>
                <p class="text-green-400 font-semibold">Total</p>
                <p class="text-green-400 font-bold">${{ number_format($this->total, 2) }}</p>
            </div>

        </div>

        <hr class="my-3 border-green-700/30">

        <div class="space-y-1 text-sm">

            <div>
                <p class="text-green-400 font-semibold">Cliente</p>
                <p class="text-gray-200">{{ $nombre ?: '—' }}</p>
            </div>

            <div>
                <p class="text-green-400 font-semibold">Teléfono</p>
                <p class="text-gray-200">{{ $telefono ?: '—' }}</p>
            </div>

            <div>
                <p class="text-green-400 font-semibold">Email</p>
                <p class="text-gray-200">{{ $email ?: '—' }}</p>
            </div>

            <div>
                <p class="text-green-400 font-semibold">Equipo</p>
                <p class="text-gray-200">{{ $equipo ?: '—' }}</p>
            </div>

            <div>
                <p class="text-green-400 font-semibold">Notas</p>
                <p class="text-gray-200">{{ $notas ?: 'Sin observaciones' }}</p>
            </div>

        </div>

    </div>
</div>


</div>

@if($mostrarModalConfirmacion)
<div class="fixed inset-0 bg-black/50 flex items-center justify-center z-50">
    <div class="bg-white p-6 rounded-xl shadow-xl w-96 border border-green-600">

        <h2 class="text-xl font-bold text-green-700 mb-3">
            ¿Guardar reserva?
        </h2>

        <p class="text-gray-600 mb-6">
            ¿Estás seguro que deseas registrar esta reserva?
        </p>

        <div class="flex justify-end gap-3">
            <button
                wire:click="cancelarConfirmacion"
                class="px-4 py-2 rounded-lg border border-gray-400 text-gray-600 hover:bg-gray-100">
                Cancelar
            </button>

            <button
                wire:click="confirmarGuardado"
                class="px-4 py-2 rounded-lg bg-green-600 text-white hover:bg-green-500 shadow">
                Confirmar
            </button>
        </div>

    </div>
</div>
@endif

@if($mostrarModalExito)
<div class="fixed inset-0 bg-black/50 flex items-center justify-center z-50">
    <div class="bg-white p-6 rounded-xl shadow-xl w-96 border border-green-600">

        <h2 class="text-xl font-bold text-green-700 mb-2">
            ¡Reserva creada!
        </h2>

        <p class="text-gray-600 mb-6">
            La reserva ha sido registrada correctamente.
        </p>

        <div class="flex justify-center">
            <button
                wire:click="$set('mostrarModalExito', false)"
                class="px-6 py-2 rounded-lg bg-green-600 text-white hover:bg-green-500 shadow">
                Aceptar
            </button>
        </div>

    </div>
</div>
@endif

    </div>

</section>
</div>
