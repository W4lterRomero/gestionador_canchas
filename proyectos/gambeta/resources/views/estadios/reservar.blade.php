@extends('components.layouts.app')

@section('content')

<section class="container mx-auto px-6 py-10">

    <!-- Título -->
    <h1 class="text-3xl font-bold text-green-700 mb-6">
        Crear nueva reserva
    </h1>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-10">

        <!-- Formulario de Reserva -->
        <div class="md:col-span-2 bg-white rounded-2xl shadow-xl p-8 space-y-10 border border-green-200">

            <!-- Datos del Cliente -->
            <div>
                <h2 class="text-2xl font-bold text-green-700 mb-4">Datos del Cliente</h2>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">

                    <!-- Nombre -->
                    <div>
                        <label class="font-semibold text-gray-700">Nombre completo</label>
                        <input type="text"
                            class="mary-input mary-input-bordered bg-gray-50 w-full"
                            placeholder="Ej: Carlos Pérez">
                    </div>

                    <!-- Teléfono -->
                    <div>
                        <label class="font-semibold text-gray-700">Teléfono</label>
                        <input type="text"
                            class="mary-input mary-input-bordered bg-gray-50 w-full"
                            placeholder="0000-0000">
                    </div>

                    <!-- Equipo -->
                    <div>
                        <label class="font-semibold text-gray-700">Equipo o grupo</label>
                        <input type="text"
                            class="mary-input mary-input-bordered bg-gray-50 w-full"
                            placeholder="Nombre del equipo">
                    </div>

                    <!-- DUI -->
                    <div>
                        <label class="font-semibold text-gray-700">DUI</label>
                        <input type="text"
                            class="mary-input mary-input-bordered bg-gray-50 w-full"
                            placeholder="00000000-0">
                    </div>

                    <!-- Dirección -->
                    <div class="md:col-span-2">
                        <label class="font-semibold text-gray-700">Dirección</label>
                        <input type="text"
                            class="mary-input mary-input-bordered bg-gray-50 w-full"
                            placeholder="Colonia, calle, número de casa...">
                    </div>

                    <!-- Correo -->
                    <div>
                        <label class="font-semibold text-gray-700">Correo electrónico</label>
                        <input type="email"
                            class="mary-input mary-input-bordered bg-gray-50 w-full"
                            placeholder="cliente@mail.com">
                    </div>

                    <!-- Fecha nacimiento -->
                    <div>
                        <label class="font-semibold text-gray-700">Fecha de nacimiento</label>
                        <input type="date"
                            class="mary-input mary-input-bordered bg-gray-50 w-full">
                    </div>

                    <!-- Género -->
                    <div>
                        <label class="font-semibold text-gray-700">Género</label>
                        <select class="mary-select mary-select-bordered bg-gray-50 w-full">
                            <option value="">Seleccione</option>
                            <option>Masculino</option>
                            <option>Femenino</option>
                            <option>Otro / Prefiero no decirlo</option>
                        </select>
                    </div>

                </div>
            </div>



            <!-- Datos de la Reserva -->
            <div>
                <h2 class="text-2xl font-bold text-green-700 mb-4">Datos de la Reserva</h2>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">

                    <!-- Estadio -->
                    <div>
                        <label class="font-semibold text-gray-700">Estadio</label>
                        <select class="mary-select mary-select-bordered bg-gray-50 w-full">
                            <option>Estadio Juan Francisco Barraza</option>
                            <option>Estadio Félix Charlaix</option>
                            <option>Estadio Ramón Flores Berríos</option>
                            <option>Estadio Marcelino Imbers</option>
                            <option>Estadio Correcaminos</option>
                            <option>Estadio Municipal Moncagua</option>
                        </select>
                    </div>

                    <!-- Fecha -->
                    <div>
                        <label class="font-semibold text-gray-700">Fecha</label>
                        <input type="date"
                            class="mary-input mary-input-bordered bg-gray-50 w-full">
                    </div>

                    <!-- Hora -->
                    <div>
                        <label class="font-semibold text-gray-700">Hora</label>
                        <input type="time"
                            class="mary-input mary-input-bordered bg-gray-50 w-full">
                    </div>

                    <!-- Duración -->
                    <div>
                        <label class="font-semibold text-gray-700">Duración (horas)</label>
                        <input type="number" min="1"
                            class="mary-input mary-input-bordered bg-gray-50 w-full"
                            placeholder="1">
                    </div>

                    <!-- Precio -->
                    <div>
                        <label class="font-semibold text-gray-700">Precio por hora (USD)</label>
                        <input type="number" step="0.01"
                            class="mary-input mary-input-bordered bg-gray-50 w-full"
                            placeholder="$30">
                    </div>

                    <!-- Total -->
                    <div>
                        <label class="font-semibold text-gray-700">Total a pagar (USD)</label>
                        <input type="text"
                            class="mary-input mary-input-bordered bg-gray-50 w-full"
                            placeholder="Se calculará automáticamente"
                            disabled>
                    </div>

                </div>
            </div>



            <!-- Estado de la Reserva -->
            <div>
                <h2 class="text-2xl font-bold text-green-700 mb-4">Estado de la Reserva</h2>

                <select class="mary-select mary-select-bordered bg-gray-50 w-full md:w-1/3">
                    <option>Pendiente</option>
                    <option>Confirmada</option>
                    <option>Cancelada</option>
                    <option>Finalizada</option>
                </select>
            </div>



            <!-- Pago y Comprobante -->
            <div>
                <h2 class="text-2xl font-bold text-green-700 mb-4">Pago y Comprobante</h2>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">

                    <!-- Estado de pago -->
                    <div>
                        <label class="font-semibold text-gray-700">Estado del pago</label>
                        <select class="mary-select mary-select-bordered bg-gray-50 w-full">
                            <option>No pagado</option>
                            <option>Pagado completo</option>
                            <option>Dejó adelanto</option>
                        </select>
                    </div>

                    <!-- Adelanto -->
                    <div>
                        <label class="font-semibold text-gray-700">Monto de adelanto (USD)</label>
                        <input type="number" step="0.01"
                            class="mary-input mary-input-bordered bg-gray-50 w-full"
                            placeholder="Ej: 10.00">
                    </div>

                    <!-- Método de pago -->
                    <div>
                        <label class="font-semibold text-gray-700">Método de pago</label>
                        <select class="mary-select mary-select-bordered bg-gray-50 w-full">
                            <option>Efectivo</option>
                            <option>Tarjeta</option>
                            <option>Transferencia</option>
                            <option>Depósito bancario</option>
                        </select>
                    </div>

                </div>

                <!-- Botón PDF -->
                <div class="mt-6 flex justify-center">
                    <button class="mary-btn bg-green-600 hover:bg-green-500 text-white rounded-full px-8 py-2 shadow">
                        Generar comprobante PDF
                    </button>
                </div>
            </div>

        </div>




        <!-- Detalles de Compra -->
        <div class="md:col-span-1">

            <div class="bg-gray-900 text-white rounded-2xl shadow-xl p-6 border border-green-500/30">

                <h2 class="text-2xl font-bold mb-4 flex items-center gap-2">
                    <i class="fa-solid fa-cart-shopping text-green-400"></i>
                    Detalles de Compra
                </h2>

                <div class="bg-gray-800 rounded-xl p-4 flex items-center gap-3 border border-gray-700 mb-4">
                    <i class="fa-solid fa-circle-info text-yellow-400 text-2xl"></i>
                    <p class="text-gray-300 text-sm">Complete la información para ver el resumen.</p>
                </div>

                <!-- Datos ejemplo visual -->
                <div class="space-y-4">

                    <div class="p-4 bg-gray-800 rounded-xl border border-green-600/20">
                        <p class="font-semibold text-green-400">Estadio</p>
                        <p class="text-gray-200">–</p>
                    </div>

                    <div class="p-4 bg-gray-800 rounded-xl border border-green-600/20">
                        <p class="font-semibold text-green-400">Fecha</p>
                        <p class="text-gray-200">–</p>
                    </div>

                    <div class="p-4 bg-gray-800 rounded-xl border border-green-600/20">
                        <p class="font-semibold text-green-400">Hora</p>
                        <p class="text-gray-200">–</p>
                    </div>

                    <div class="p-4 bg-gray-800 rounded-xl border border-green-600/20">
                        <p class="font-semibold text-green-400">Duración</p>
                        <p class="text-gray-200">–</p>
                    </div>

                    <div class="p-4 bg-gray-800 rounded-xl border border-green-600/20">
                        <p class="font-semibold text-green-400">Precio por hora</p>
                        <p class="text-gray-200">–</p>
                    </div>

                    <div class="p-4 bg-gray-800 rounded-xl border border-green-600/20">
                        <p class="font-semibold text-green-400">Total</p>
                        <p class="text-green-400 font-bold text-xl">–</p>
                    </div>

                </div>

                <!-- Cupón -->
                <div class="mt-6">
                    <p class="text-gray-400 mb-1">¿Tiene un cupón?</p>
                    <div class="flex gap-2">
                        <input type="text" placeholder="Cupón"
                            class="mary-input bg-gray-800 border border-green-500/30 text-white w-full">
                        <button class="bg-green-600 hover:bg-green-500 px-4 rounded-xl font-bold">
                            Aplicar
                        </button>
                    </div>
                </div>

                <!-- Botón Final -->
                <div class="mt-6 flex justify-center">
                    <button class="mary-btn bg-green-600 hover:bg-green-500 text-white font-bold px-6 py-2 rounded-full shadow-lg">
                        Confirmar Reserva
                    </button>
                </div>

            </div>

        </div>

    </div>

</section>

@endsection
