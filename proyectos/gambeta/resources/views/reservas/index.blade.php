@extends('components.layouts.app')

@section('content')

<section class="container mx-auto px-6 py-10 space-y-12">

    <!-- =================================================================== -->
    <!-- 🔵 TÍTULO PRINCIPAL -->
    <!-- =================================================================== -->
    <h1 class="text-3xl font-bold text-green-700 mb-4">
        Gestión de Reservas
    </h1>

    <p class="text-gray-600 mb-8">
        Consulta, modifica y administra todas las reservas del sistema.
    </p>


    <!-- =================================================================== -->
    <!-- 🔵 FILTROS -->
    <!-- =================================================================== -->
    <div class="bg-white rounded-2xl shadow-md p-6 border border-green-300">

        <h2 class="text-xl font-bold text-green-700 mb-4 flex items-center gap-2">
            <i class="fa-solid fa-filter text-green-500"></i>
            Filtros de búsqueda
        </h2>

        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">

            <!-- Cliente -->
            <input type="text"
                class="mary-input mary-input-bordered bg-gray-50"
                placeholder="Buscar por cliente...">

            <!-- Estadio -->
            <select class="mary-select mary-select-bordered bg-gray-50">
                <option value="">Todos los estadios</option>
                <option>Estadio Barraza</option>
                <option>Estadio Charlaix</option>
                <option>Estadio Imbers</option>
                <option>Estadio Correcaminos</option>
            </select>

            <!-- Fecha -->
            <input type="date"
                class="mary-input mary-input-bordered bg-gray-50">

            <!-- Estado -->
            <select class="mary-select mary-select-bordered bg-gray-50">
                <option value="">Estado</option>
                <option>Pendiente</option>
                <option>Confirmada</option>
                <option>Cancelada</option>
                <option>Finalizada</option>
            </select>

        </div>
    </div>

    
    <!-- =================================================================== -->
    <!-- 🔵 TABLA DE RESERVAS -->
    <!-- =================================================================== -->
    <div class="bg-white rounded-2xl shadow-md p-6 border border-green-300">

        <h2 class="text-xl font-bold text-green-700 mb-6 flex items-center gap-2">
            <i class="fa-solid fa-list-check text-green-500"></i>
            Todas las reservas registradas
        </h2>

        <div class="overflow-x-auto">
            <table class="table w-full">

                <thead>
                    <tr class="text-green-700 font-bold text-sm">
                        <th>Cliente</th>
                        <th>Estadio</th>
                        <th>Fecha</th>
                        <th>Hora</th>
                        <th>Duración</th>
                        <th>Total</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>

                <tbody class="text-sm">

                    <!-- ======================= -->
                    <!-- RESERVA EJEMPLO 1 -->
                    <!-- ======================= -->
                    <tr>
                        <td>Carlos Pérez</td>
                        <td>Estadio Barraza</td>
                        <td>2025-05-10</td>
                        <td>17:00</td>
                        <td>2h</td>
                        <td>$60</td>

                        <td>
                            <select class="mary-select mary-select-bordered text-sm bg-gray-50">
                                <option>Pendiente</option>
                                <option>Confirmada</option>
                                <option>Cancelada</option>
                                <option>Finalizada</option>
                            </select>
                        </td>

                        <td class="flex gap-2">

                            <a href="{{ route('reservas.editar', 1) }}"
                               class="mary-btn mary-btn-xs bg-green-600 hover:bg-green-500 text-white rounded">
                                Editar
                            </a>

                            <button class="mary-btn mary-btn-xs bg-red-600 hover:bg-red-500 text-white rounded"
                                    onclick="confirm('¿Eliminar reserva?')">
                                Eliminar
                            </button>

                        </td>
                    </tr>


                    <!-- ======================= -->
                    <!-- RESERVA EJEMPLO 2 -->
                    <!-- ======================= -->
                    <tr>
                        <td>Andrea Gómez</td>
                        <td>Estadio Charlaix</td>
                        <td>2025-05-12</td>
                        <td>18:00</td>
                        <td>1h</td>
                        <td>$35</td>

                        <td>
                            <select class="mary-select mary-select-bordered text-sm bg-gray-50">
                                <option>Confirmada</option>
                                <option>Pendiente</option>
                                <option>Cancelada</option>
                                <option>Finalizada</option>
                            </select>
                        </td>

                        <td class="flex gap-2">

                            <a href="{{ route('reservas.editar', 2) }}"
                               class="mary-btn mary-btn-xs bg-green-600 hover:bg-green-500 text-white rounded">
                                Editar
                            </a>

                            <button class="mary-btn mary-btn-xs bg-red-600 hover:bg-red-500 text-white rounded"
                                    onclick="confirm('¿Eliminar reserva?')">
                                Eliminar
                            </button>

                        </td>
                    </tr>


                    <!-- ======================= -->
                    <!-- RESERVA EJEMPLO 3 -->
                    <!-- ======================= -->
                    <tr>
                        <td>Mario López</td>
                        <td>Estadio Imbers</td>
                        <td>2025-05-14</td>
                        <td>14:00</td>
                        <td>3h</td>
                        <td>$90</td>

                        <td>
                            <select class="mary-select mary-select-bordered text-sm bg-gray-50">
                                <option>Finalizada</option>
                                <option>Pendiente</option>
                                <option>Confirmada</option>
                                <option>Cancelada</option>
                            </select>
                        </td>

                        <td class="flex gap-2">

                            <a href="{{ route('reservas.editar', 3) }}"
                               class="mary-btn mary-btn-xs bg-green-600 hover:bg-green-500 text-white rounded">
                                Editar
                            </a>

                            <button class="mary-btn mary-btn-xs bg-red-600 hover:bg-red-500 text-white rounded"
                                    onclick="confirm('¿Eliminar reserva?')">
                                Eliminar
                            </button>

                        </td>
                    </tr>

                </tbody>

            </table>
        </div>

    </div>

</section>

@endsection
