<div>
<section class="container mx-auto px-6 py-10">

    <!--              PASOS SUPERIORES VERDES             -->

    <div class="w-full mb-10 select-none">

        <div class="flex items-center justify-between relative">

            <!-- Línea -->
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


        <!--               CONTENIDO IZQUIERDO DE LOS PASOS            -->
        <div class="md:col-span-2 bg-white rounded-2xl shadow-xl p-8 space-y-10 border border-green-200">


            <!--        PASO 1: RESERVAR CANCHA + PRECIO TOTAL            -->

        <!-- PASO 1 -->
@if ($pasoActual == 1)
<div class="space-y-6">

    <h2 class="text-2xl font-bold text-green-700">Reservar cancha</h2>

    <!-- SELECT CANCHA -->
    <div>
        <label class="font-semibold text-gray-700">Cancha</label>
        <select wire:model.live="cancha"
            class="w-full bg-gray-100 border border-green-600 rounded-xl px-4 py-2">
            <option value="">-- Seleccionar --</option>
            @foreach($canchas as $c)
                <option value="{{ $c->id }}">{{ $c->nombre }}</option>
            @endforeach
        </select>
    </div>

    @if ($canchaObj)
    <!-- TARJETA -->
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

    <!-- FILA 1: FECHA + HORAS -->
    <div class="flex flex-col md:flex-row gap-6 items-start">

        <div class="w-full">
            @livewire('calendario-reserva', [
                'fechaInicial' => $fecha,
                'canchaId'     => $cancha
            ])
        </div>

        <div class="flex-1">
        </div>

    </div>

    <!-- FILA 2: DURACIÓN + PRECIO -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

        <!-- DURACION -->
        <div>
            <label class="font-semibold text-gray-700">Duración (horas)</label>
        <input
            type="text"
            wire:model.live="duracion"
            inputmode="numeric"
            pattern="[0-9]*"
            maxlength="2"
            oninput="this.value = this.value.replace(/[^0-9]/g, '');"
            onblur="
                if(this.value === '') this.value = 1;
                if(parseInt(this.value) > 23) this.value = 23;
                if(parseInt(this.value) < 1) this.value = 1;
            "
            class="w-full bg-gray-100 border border-green-600 rounded-xl px-4 py-2"
        />

        </div>

        <!-- PRECIO -->
        <input type="text"
            wire:model="precioHora"
            class="w-full bg-gray-100 border border-green-600 rounded-xl px-4 py-2 text-gray-600"
            readonly>

    </div>

    <!-- =============================== -->
    <!-- FILA 3: TOTAL -->
    <!-- =============================== -->
    <div class="p-4 bg-green-100 rounded-xl border border-green-300">
        <p class="text-gray-700 font-semibold">Total:</p>
        <p class="text-3xl font-bold text-green-700">
            ${{ number_format($this->total, 2) }}
        </p>
    </div>

    @endif

    <!-- BOTÓN -->
    <div class="flex justify-end">
        <button wire:click="siguiente"
            class="bg-green-600 hover:bg-green-500 text-white px-8 py-2 rounded-full font-bold shadow">
            Continuar
        </button>
    </div>

</div>
@endif

            <!--        PASO 2: REGISTRAR CLIENTE                        -->

    @if ($pasoActual == 2)
    <div class="space-y-6">

        <h2 class="text-2xl font-bold text-green-700">
            Datos del cliente
        </h2>

        {{-- BUSCADOR --}}
        <div class="flex items-center gap-3 relative">
            <span class="text-green-600 text-xl">
                <i class="fa-solid fa-magnifying-glass"></i>
            </span>

            <input type="text"
                wire:model.live="busqueda"
                placeholder="Buscar cliente por nombre..."
                class="w-full bg-gray-100 border border-green-600 rounded-xl px-4 py-2"
            >

            @if(count($resultados) > 0)
                <div class="absolute left-8 top-full w-full bg-white border border-green-200 mt-1 rounded-xl shadow-lg z-50">
                    @foreach($resultados as $cli)
                        <div wire:click="seleccionarCliente({{ $cli->id }})"
                            class="p-2 cursor-pointer hover:bg-green-100">
                            {{ $cli->nombre }} - {{ $cli->telefono }}
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        {{-- FORMULARIO --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

            <input type="text" wire:model="nombre"
                @if($clienteExiste) disabled @endif
                class="bg-gray-100 border border-green-600 rounded-xl px-4 py-2"
                placeholder="Nombre completo">

            <input type="text" wire:model="telefono"
                @if($clienteExiste) disabled @endif
                placeholder="Teléfono ####-####"
                class="bg-gray-100 border border-green-600 rounded-xl px-4 py-2">

            <input type="text" wire:model="equipo"
                @if($clienteExiste) disabled @endif
                placeholder="Equipo (opcional)"
                class="bg-gray-100 border border-green-600 rounded-xl px-4 py-2">

        </div>

        <textarea wire:model="notas"
            @if($clienteExiste) disabled @endif
            class="w-full bg-gray-100 border border-green-600 rounded-xl px-4 py-2"
            placeholder="Notas adicionales"></textarea>

        <div class="flex justify-between">
            <button wire:click="anterior" class="text-green-600 font-bold">← Volver</button>
            <button wire:click="siguiente"
                class="bg-green-600 hover:bg-green-500 text-white px-8 py-2 rounded-full font-bold shadow">
                Continuar
            </button>
        </div>

    </div>
    @endif

            <!--        PASO 3: PAGO (PAGADO / ADELANTO / PDF)           -->

@if ($pasoActual == 3)
<div class="space-y-6">

    <h2 class="text-2xl font-bold text-green-700">
        Pago del cliente
    </h2>

    <div class="space-y-4">

        <div>
            <label class="font-semibold text-gray-700">Estado de la reserva</label>
            <select wire:model="estadoReserva"
                class="w-full bg-gray-100 border border-green-600 rounded-xl px-4 py-2">
                <option value="">-- Seleccionar --</option>
                <option value="pendiente">Pendiente</option>
                <option value="confirmada">Confirmada</option>
                <option value="cancelada">Cancelada</option>
                <option value="finalizada">Finalizada</option>
            </select>
        </div>

        <div>
            <label class="font-semibold text-gray-700">Estado del pago</label>
            <select wire:model="estadoPago"
                class="w-full bg-gray-100 border border-green-600 rounded-xl px-4 py-2">
                <option value="">-- Seleccionar --</option>
                <option value="pagado">Pagado completo</option>
                <option value="adelanto">Dejó adelanto</option>
                <option value="nopago">No pagó</option>
            </select>
        </div>

        @if ($estadoPago === 'adelanto')
        <div>
            <label class="font-semibold text-gray-700">Monto del adelanto</label>
            <input type="number" wire:model="adelanto" step="0.01"
                class="w-full bg-gray-100 border border-green-600 rounded-xl px-4 py-2">
        </div>
        @endif

        <div>
            <label class="font-semibold text-gray-700">Método de pago</label>
            <select wire:model="metodoPago"
                class="w-full bg-gray-100 border border-green-600 rounded-xl px-4 py-2">
                <option value="">-- Seleccionar --</option>
                <option value="efectivo">Efectivo</option>
                <option value="tarjeta">Tarjeta</option>
                <option value="transferencia">Transferencia</option>
            </select>
        </div>

        <div>
            <label class="font-semibold text-gray-700">Observaciones</label>
            <input type="text"
                wire:model="observaciones"
                class="w-full bg-gray-100 border border-green-600 rounded-xl px-4 py-2"
                placeholder="Escribe algo (opcional)">
        </div>

        <div class="text-center pt-4">
            <button
                class="bg-green-600 hover:bg-green-500 text-white px-8 py-2 rounded-full font-bold shadow">
                Generar comprobante PDF
            </button>
        </div>

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
        <button wire:click="finalizar"
            class="bg-green-700 hover:bg-green-600 text-white px-8 py-2 rounded-full font-bold shadow">
            Finalizar reserva
        </button>
    </div>
</div>
@endif

        </div>

        <!--                RESUMEN DERECHA (EN VIVO)                  -->

<div class="md:col-span-1">

    <div class="bg-gray-900 text-white rounded-2xl shadow-xl p-5 border border-green-500/30">

        <h2 class="text-xl font-bold mb-3 flex items-center gap-2">
            <i class="fa-solid fa-cart-shopping text-green-400"></i>
            Resumen
        </h2>

        <!-- PRIMERA SECCIÓN -->
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

        <!-- SEPARADOR -->
        <hr class="my-3 border-green-700/30">

        <!-- SEGUNDA SECCIÓN -->
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


    </div>

</section>
</div>
