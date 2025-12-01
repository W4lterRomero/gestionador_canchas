@extends('components.layouts.app')

@section('content')

<section class="container mx-auto px-6 py-10">

    <!-- TÍTULO PRINCIPAL -->
    <h1 class="text-3xl font-bold text-green-700 mb-8">
        Gestión de Reservas
    </h1>

    <!-- =============================== -->
    <!-- FILTROS -->
    <!-- =============================== -->
    <div class="bg-white rounded-2xl shadow-lg p-6 border border-green-300 mb-10">

        <h2 class="text-xl font-bold text-green-700 mb-4">Filtros de Reservas</h2>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">

            <!-- Buscar por Cliente -->
            <div>
                <label class="font-semibold text-gray-700">Buscar por cliente</label>
                <input type="text" placeholder="Ej: Carlos Pérez" 
                       class="mary-input mary-input-bordered bg-gray-50 w-full">
            </div>

            <!-- Filtrar por Estadio -->
            <div>
                <label class="font-semibold text-gray-700">Filtrar por estadio</label>
                <select class="mary-select mary-select-bordered bg-gray-50 w-full">
                    <option value="">Todos</option>
                    <option>Estadio Juan Francisco Barraza</option>
                    <option>Estadio Félix Charlaix</option>
                    <option>Estadio Ramón Flores Berríos</option>
                    <option>Estadio Marcelino Imbers</option>
                    <option>Estadio Correcaminos</option>
                    <option>Estadio Municipal Moncagua</option>
                </select>
            </div>

            <!-- Filtrar por Estado -->
            <div>
                <label class="font-semibold text-gray-700">Estado</label>
                <select class="mary-select mary-select-bordered bg-gray-50 w-full">
                    <option value="">Todos</option>
                    <option>Pendiente</option>
                    <option>Confirmada</option>
                    <option>Cancelada</option>
                    <option>Finalizada</option>
                </select>
            </div>

        </div>
    </div>




    <!-- =============================== -->
    <!-- TABLA DE RESERVAS -->
    <!-- =============================== -->
    <div class="bg-white rounded-2xl shadow-lg p-6 border border-green-300">

        <h2 class="text-xl font-bold text-green-700 mb-4">Listado de Reservas</h2>

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

                    <!-- EJEMPLO 1 -->
                    <tr>
                        <td>Carlos Pérez</td>
                        <td>Estadio Barraza</td>
                        <td>2025-05-10</td>
                        <td>17:00</td>
                        <td>2h</td>
                        <td>$60</td>
                        <td><span class="mary-badge mary-badge-warning">Pendiente</span></td>
                        <td>
                            <button class="mary-btn mary-btn-xs bg-green-600 hover:bg-green-500 text-white">Ver</button>
                        </td>
                    </tr>

                    <!-- EJEMPLO 2 -->
                    <tr>
                        <td>José Martínez</td>
                        <td>Estadio Félix Charlaix</td>
                        <td>2025-05-12</td>
                        <td>19:00</td>
                        <td>1h</td>
                        <td>$35</td>
                        <td><span class="mary-badge mary-badge-success">Confirmada</span></td>
                        <td>
                            <button class="mary-btn mary-btn-xs bg-green-600 hover:bg-green-500 text-white">Ver</button>
                        </td>
                    </tr>

                    <!-- EJEMPLO 3 -->
                    <tr>
                        <td>Ana Gómez</td>
                        <td>Estadio Moncagua</td>
                        <td>2025-05-07</td>
                        <td>15:00</td>
                        <td>3h</td>
                        <td>$75</td>
                        <td><span class="mary-badge mary-badge-error">Cancelada</span></td>
                        <td>
                            <button class="mary-btn mary-btn-xs bg-green-600 hover:bg-green-500 text-white">Ver</button>
                        </td>
                    </tr>

                </tbody>
            </table>
        </div>

    </div>




    <!-- =============================== -->
    <!-- CLIENTES FRECUENTES -->
    <!-- =============================== -->

    <div class="bg-white rounded-2xl shadow-lg mt-12 p-6 border border-green-300">

        <h2 class="text-xl font-bold text-green-700 mb-4">
            Clientes Frecuentes
        </h2>

        <p class="text-gray-600 mb-6 text-sm">
            Estos clientes han realizado varias reservas recientemente.
        </p>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">

            <!-- Cliente 1 -->
            <div class="bg-gray-50 p-5 rounded-xl shadow border border-green-200 hover:border-green-400 hover:shadow-lg transition">
                <h3 class="text-green-700 font-bold text-lg">Carlos Pérez</h3>
                <p class="text-gray-600 text-sm mt-1">Reservas realizadas: <b>12</b></p>
                <p class="text-gray-600 text-sm">Tel: 7012-3321</p>
                <button class="mary-btn mary-btn-sm bg-green-600 hover:bg-green-500 text-white mt-3">
                    Ver historial
                </button>
            </div>

            <!-- Cliente 2 -->
            <div class="bg-gray-50 p-5 rounded-xl shadow border border-green-200 hover:border-green-400 hover:shadow-lg transition">
                <h3 class="text-green-700 font-bold text-lg">José Martínez</h3>
                <p class="text-gray-600 text-sm mt-1">Reservas realizadas: <b>9</b></p>
                <p class="text-gray-600 text-sm">Tel: 7201-9983</p>
                <button class="mary-btn mary-btn-sm bg-green-600 hover:bg-green-500 text-white mt-3">
                    Ver historial
                </button>
            </div>

            <!-- Cliente 3 -->
            <div class="bg-gray-50 p-5 rounded-xl shadow border border-green-200 hover:border-green-400 hover:shadow-lg transition">
                <h3 class="text-green-700 font-bold text-lg">Ana Gómez</h3>
                <p class="text-gray-600 text-sm mt-1">Reservas realizadas: <b>7</b></p>
                <p class="text-gray-600 text-sm">Tel: 7566-4182</p>
                <button class="mary-btn mary-btn-sm bg-green-600 hover:bg-green-500 text-white mt-3">
                    Ver historial
                </button>
            </div>

        </div>

    </div>

</section>

@endsection
