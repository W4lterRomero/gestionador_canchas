@extends('components.layouts.app')

@section('content')

<section class="container mx-auto px-6 py-10">

    <!-- TÍTULO -->
    <h1 class="text-3xl font-bold text-green-700 mb-6">
        Editar Reserva
    </h1>

    <div class="bg-white rounded-2xl shadow-xl p-8 space-y-12 border border-green-200">

        <!-- ========================================================= -->
        <!-- DATOS DEL CLIENTE -->
        <!-- ========================================================= -->
        <div>
            <h2 class="text-2xl font-bold text-green-700 mb-4">Datos del Cliente</h2>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">

                <!-- Nombre -->
                <div>
                    <label class="font-semibold text-gray-700">Nombre completo</label>
                    <input type="text"
                        class="mary-input mary-input-bordered bg-gray-50 w-full"
                        value="Carlos Pérez">
                </div>

                <!-- Teléfono -->
                <div>
                    <label class="font-semibold text-gray-700">Teléfono</label>
                    <input type="text"
                        class="mary-input mary-input-bordered bg-gray-50 w-full"
                        value="7012-8899">
                </div>

                <!-- Equipo -->
                <div>
                    <label class="font-semibold text-gray-700">Equipo o grupo</label>
                    <input type="text"
                        class="mary-input mary-input-bordered bg-gray-50 w-full"
                        value="Los Panteras">
                </div>

                <!-- DUI -->
                <div>
                    <label class="font-semibold text-gray-700">DUI</label>
                    <input type="text"
                        class="mary-input mary-input-bordered bg-gray-50 w-full"
                        value="12345678-9">
                </div>

                <!-- Dirección -->
                <div class="md:col-span-2">
                    <label class="font-semibold text-gray-700">Dirección</label>
                    <input type="text"
                        class="mary-input mary-input-bordered bg-gray-50 w-full"
                        value="Col. Centroamérica, San Miguel">
                </div>

                <!-- Correo -->
                <div>
                    <label class="font-semibold text-gray-700">Correo electrónico</label>
                    <input type="email"
                        class="mary-input mary-input-bordered bg-gray-50 w-full"
                        value="carlos@example.com">
                </div>

                <!-- Fecha nacimiento -->
                <div>
                    <label class="font-semibold text-gray-700">Fecha de nacimiento</label>
                    <input type="date"
                        class="mary-input mary-input-bordered bg-gray-50 w-full"
                        value="1996-10-12">
                </div>

                <!-- Género -->
                <div>
                    <label class="font-semibold text-gray-700">Género</label>
                    <select class="mary-select mary-select-bordered bg-gray-50 w-full">
                        <option>Masculino</option>
                        <option>Femenino</option>
                        <option>Otro</option>
                    </select>
                </div>

            </div>
        </div>




        <!-- ========================================================= -->
        <!-- DATOS DE LA RESERVA -->
        <!-- ========================================================= -->
        <div>
            <h2 class="text-2xl font-bold text-green-700 mb-4">Datos de la Reserva</h2>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">

                <!-- Estadio -->
                <div>
                    <label class="font-semibold text-gray-700">Estadio</label>
                    <select class="mary-select mary-select-bordered bg-gray-50 w-full">
                        <option>Estadio Juan Francisco Barraza</option>
                        <option selected>Estadio Félix Charlaix</option>
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
                        class="mary-input mary-input-bordered bg-gray-50 w-full"
                        value="2025-05-10">
                </div>

                <!-- Hora -->
                <div>
                    <label class="font-semibold text-gray-700">Hora</label>
                    <input type="time"
                        class="mary-input mary-input-bordered bg-gray-50 w-full"
                        value="17:00">
                </div>

                <!-- Duración -->
                <div>
                    <label class="font-semibold text-gray-700">Duración (horas)</label>
                    <input type="number" min="1"
                        class="mary-input mary-input-bordered bg-gray-50 w-full"
                        value="2">
                </div>

                <!-- Precio -->
                <div>
                    <label class="font-semibold text-gray-700">Precio por hora (USD)</label>
                    <input type="number" step="0.01"
                        class="mary-input mary-input-bordered bg-gray-50 w-full"
                        value="30">
                </div>

                <!-- Total -->
                <div>
                    <label class="font-semibold text-gray-700">Total a pagar (USD)</label>
                    <input type="text"
                        class="mary-input mary-input-bordered bg-gray-50 w-full"
                        value="$60" disabled>
                </div>

            </div>
        </div>




        <!-- ========================================================= -->
        <!-- ESTADO DE LA RESERVA -->
        <!-- ========================================================= -->
        <div>
            <h2 class="text-2xl font-bold text-green-700 mb-4">Estado de la Reserva</h2>

            <select class="mary-select mary-select-bordered bg-gray-50 w-full md:w-1/3">
                <option>Pendiente</option>
                <option selected>Confirmada</option>
                <option>Cancelada</option>
                <option>Finalizada</option>
            </select>
        </div>




        <!-- ========================================================= -->
        <!-- DETALLES DE PAGO / COMPROBANTE -->
        <!-- ========================================================= -->
        <div>
            <h2 class="text-2xl font-bold text-green-700 mb-4 flex items-center gap-2">
                <i class="fa-solid fa-receipt text-green-600"></i>
                Detalles de Pago
            </h2>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">

                <!-- Pago realizado -->
                <div>
                    <label class="font-semibold text-gray-700">¿Pago realizado?</label>
                    <select class="mary-select mary-select-bordered bg-gray-50 w-full">
                        <option selected>Completo</option>
                        <option>Adelanto</option>
                        <option>No pagado</option>
                    </select>
                </div>

                <!-- Monto pagado -->
                <div>
                    <label class="font-semibold text-gray-700">Monto pagado</label>
                    <input type="number" step="0.01"
                        class="mary-input mary-input-bordered bg-gray-50 w-full"
                        value="60.00">
                </div>

                <!-- Comprobante -->
                <div>
                    <label class="font-semibold text-gray-700">Comprobante (PDF / Imagen)</label>
                    <input type="file"
                        class="mary-input mary-input-bordered bg-gray-50 w-full">
                </div>

            </div>

            <button class="mary-btn bg-green-600 hover:bg-green-500 text-white rounded-full mt-4 px-6">
                Descargar comprobante PDF
            </button>
        </div>




        <!-- ========================================================= -->
        <!-- BOTONES -->
        <!-- ========================================================= -->
        <div class="flex justify-end gap-4 pt-6">

            <a href="{{ route('reservas.index') }}"
               class="mary-btn bg-gray-300 hover:bg-gray-400 text-gray-800 rounded-full px-6">
                Cancelar
            </a>

            <button class="mary-btn bg-green-600 hover:bg-green-500 text-white rounded-full px-6">
                Guardar Cambios
            </button>

        </div>

    </div>

</section>

@endsection
