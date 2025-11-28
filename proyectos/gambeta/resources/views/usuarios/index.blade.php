@extends('components.layouts.app')

@section('content')

<section class="container mx-auto px-6 py-10">

    <!-- ENCABEZADO -->
    <div class="flex justify-between items-center mb-8">

        <div>
            <h1 class="text-3xl font-bold text-green-700">Gestión de Usuarios</h1>
            <p class="text-gray-600">Administración de cuentas y roles del sistema.</p>
        </div>

        <!-- Botón crear usuario -->
        <button 
            data-bs-toggle="modal" 
            data-bs-target="#modalCrearUsuario"
            class="mary-btn bg-green-600 hover:bg-green-500 text-white rounded-full px-6 shadow">
            <i class="fa-solid fa-user-plus mr-2"></i> Nuevo Usuario
        </button>
    </div>


    <!-- FILTROS -->
    <div class="bg-white rounded-xl shadow p-5 border border-green-200">

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

            <!-- Buscar -->
            <input 
                type="text" 
                class="mary-input mary-input-bordered bg-gray-50"
                placeholder="Buscar usuario por nombre...">

            <!-- Rol -->
            <select class="mary-select mary-select-bordered bg-gray-50">
                <option value="">Filtrar por rol</option>
                <option>Administrador</option>
                <option>Recepción</option>
                <option>Supervisor</option>
            </select>

        </div>

    </div>


    <!-- TABLA DE USUARIOS -->
    <div class="mt-8 bg-white rounded-2xl shadow-xl overflow-hidden border border-green-200">

        <table class="table table-zebra w-full">
            <thead class="bg-green-600 text-white">
                <tr>
                    <th>Nombre</th>
                    <th>Usuario</th>
                    <th>Rol</th>
                    <th>Teléfono</th>
                    <th>Estado</th>
                    <th class="text-center">Acciones</th>
                </tr>
            </thead>

            <tbody>

                <!-- EJEMPLO 1 -->
                <tr>
                    <td>Carlos Pérez</td>
                    <td>cperez</td>
                    <td>Administrador</td>
                    <td>7111-2233</td>
                    <td>
                        <span class="mary-badge mary-badge-success">Activo</span>
                    </td>
                    <td class="text-center">
                        <a href="{{ route('usuarios.ver') }}" class="mary-btn mary-btn-sm bg-blue-600 text-white rounded-md hover:bg-blue-500">
                            Ver
                        </a>
                        <a href="{{ route('usuarios.editar') }}" class="mary-btn mary-btn-sm bg-yellow-500 text-white rounded-md hover:bg-yellow-400">
                            Editar
                        </a>
                        <button class="mary-btn mary-btn-sm bg-red-600 text-white rounded-md hover:bg-red-500">
                            Eliminar
                        </button>
                    </td>
                </tr>

                <!-- EJEMPLO 2 -->
                <tr>
                    <td>María López</td>
                    <td>mlopez</td>
                    <td>Recepción</td>
                    <td>7002-1100</td>
                    <td>
                        <span class="mary-badge mary-badge-success">Activo</span>
                    </td>
                    <td class="text-center">
                        <a href="{{ route('usuarios.ver') }}" class="mary-btn mary-btn-sm bg-blue-600 text-white rounded-md hover:bg-blue-500">
                            Ver
                        </a>
                        <a href="{{ route('usuarios.editar') }}" class="mary-btn mary-btn-sm bg-yellow-500 text-white rounded-md hover:bg-yellow-400">
                            Editar
                        </a>
                        <button class="mary-btn mary-btn-sm bg-red-600 text-white rounded-md hover:bg-red-500">
                            Eliminar
                        </button>
                    </td>
                </tr>

                <!-- EJEMPLO 3 -->
                <tr>
                    <td>José Martínez</td>
                    <td>jmartinez</td>
                    <td>Supervisor</td>
                    <td>7654-8899</td>
                    <td>
                        <span class="mary-badge mary-badge-warning">Suspendido</span>
                    </td>
                    <td class="text-center">
                        <a href="{{ route('usuarios.ver') }}" class="mary-btn mary-btn-sm bg-blue-600 text-white rounded-md hover:bg-blue-500">
                            Ver
                        </a>
                        <a href="{{ route('usuarios.editar') }}" class="mary-btn mary-btn-sm bg-yellow-500 text-white rounded-md hover:bg-yellow-400">
                            Editar
                        </a>
                        <button class="mary-btn mary-btn-sm bg-red-600 text-white rounded-md hover:bg-red-500">
                            Eliminar
                        </button>
                    </td>
                </tr>

            </tbody>
        </table>

    </div>



    <!-- ======================================================== -->
    <!-- MODAL: CREAR USUARIO -->
    <!-- ======================================================== -->
    <div class="modal fade" id="modalCrearUsuario" tabindex="-1">
      <div class="modal-dialog modal-lg">
        <div class="modal-content rounded-2xl overflow-hidden">

            <div class="modal-header bg-green-700 text-white">
                <h5 class="modal-title">Crear nuevo usuario</h5>
                <button class="btn-close bg-white rounded-full p-2" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                    <div>
                        <label class="font-semibold text-gray-700">Nombre completo</label>
                        <input type="text" class="mary-input mary-input-bordered bg-gray-50 w-full">
                    </div>

                    <div>
                        <label class="font-semibold text-gray-700">Usuario</label>
                        <input type="text" class="mary-input mary-input-bordered bg-gray-50 w-full">
                    </div>

                    <div>
                        <label class="font-semibold text-gray-700">Correo</label>
                        <input type="email" class="mary-input mary-input-bordered bg-gray-50 w-full">
                    </div>

                    <div>
                        <label class="font-semibold text-gray-700">Teléfono</label>
                        <input type="text" class="mary-input mary-input-bordered bg-gray-50 w-full">
                    </div>

                    <div>
                        <label class="font-semibold text-gray-700">Rol</label>
                        <select class="mary-select mary-select-bordered bg-gray-50 w-full">
                            <option>Administrador</option>
                            <option>Recepción</option>
                            <option>Supervisor</option>
                        </select>
                    </div>

                    <div>
                        <label class="font-semibold text-gray-700">Contraseña</label>
                        <input type="password" class="mary-input mary-input-bordered bg-gray-50 w-full">
                    </div>

                </div>

            </div>

            <div class="modal-footer">
                <button class="mary-btn bg-gray-300 hover:bg-gray-400 text-gray-800 rounded-full px-6" data-bs-dismiss="modal">
                    Cancelar
                </button>
                <button class="mary-btn bg-green-600 hover:bg-green-500 text-white rounded-full px-6">
                    Crear Usuario
                </button>
            </div>

        </div>
      </div>
    </div>

</section>

@endsection
