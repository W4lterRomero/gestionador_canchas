<!DOCTYPE html>
<html lang="es" data-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema Nacional de áreas deportivas</title>
    <link rel="icon" href="{{ asset('images/logo.png') }}" type="image/png">

    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdn.jsdelivr.net/npm/daisyui@4.10.1/dist/full.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background-image: url('{{ asset("images/Fondo.jpg") }}');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
        }

        /* Capa oscura sutil encima del fondo */
        .dark-layer {
            background: rgba(0, 0, 0, 0.65);
        }
    </style>
</head>

<body class="text-gray-200">

    <!-- CAPA OSCURA SOBRE EL FONDO -->
    <div class="dark-layer min-h-screen flex items-center justify-center px-4">

        <!-- TARJETA DEL LOGIN -->
        <div class="card w-full max-w-md bg-[#0f1b33]/90 shadow-2xl p-8 border border-blue-900 rounded-xl backdrop-blur-md">

            <!-- IMAGEN SUPERIOR -->
            <div class="flex justify-center mb-6">
                <img src="{{ asset('images/logo.png') }}" alt="Logo" class="h-32 w-auto object-contain drop-shadow-xl">
            </div>

            <!-- TITULO -->
            <h2 class="text-3xl font-bold text-center text-blue-300 mb-6">
                Inicio de Sesión
            </h2>

            <!-- FORMULARIO -->
            <form class="space-y-5">

                <!-- Usuario -->
                <div>
                    <label class="block mb-1 text-blue-300 font-semibold">Usuario</label>
                    <input type="text"
                        placeholder="Ingrese su usuario"
                        class="input input-bordered w-full bg-gray-800/70 border-blue-700 text-white" />
                </div>

                <!-- Contraseña -->
                <div>
                    <label class="block mb-1 text-blue-300 font-semibold">Contraseña</label>
                    <input type="password"
                        placeholder="Ingrese su contraseña"
                        class="input input-bordered w-full bg-gray-800/70 border-blue-700 text-white" />
                </div>

                <!-- Botón -->
                <a href="{{ route('home') }}" class="btn w-full bg-blue-700 hover:bg-blue-900 text-white py-2 text-lg">
                    Entrar
                </a>

            </form>

            <!-- Divider -->
            <div class="divider text-blue-300 mt-8">Sistema Nacional de Estadios Deportivos</div>

        </div>
    </div>

</body>
</html>
