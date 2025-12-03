<!DOCTYPE html>
<html lang="es" data-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema Nacional de Áreas Deportivas</title>

    <link rel="icon" href="{{ asset('images/logo.png') }}" type="image/png">

    <!-- Tailwind + DaisyUI -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdn.jsdelivr.net/npm/daisyui@4.10.1/dist/full.css" rel="stylesheet" />

    <!-- Bootstrap (solo para el modal si lo necesitas) -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background-image: url('{{ asset("images/Fondo.jpg") }}');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
        }
        .dark-layer {
            background: rgba(0, 0, 0, 0.65);
        }
    </style>
</head>

<body class="text-gray-200">

    <div class="dark-layer min-h-screen flex items-center justify-center px-4">

        <div class="card w-full max-w-md bg-green-900/70 shadow-2xl p-8 border border-green-500 rounded-2xl backdrop-blur-xl">

            <div class="flex justify-center mb-6">
                <img src="{{ asset('images/logo.png') }}" alt="Logo" 
                     class="h-32 w-auto object-contain drop-shadow-xl">
            </div>

            <h2 class="text-3xl font-bold text-center text-green-300 mb-6 tracking-wide">
                Inicio de Sesión
            </h2>

            @if ($errors->any())
            <div class="alert alert-error mb-4 bg-red-600/80 text-white rounded-lg p-3">
                <ul class="list-disc list-inside text-sm">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <form class="space-y-5" method="POST" action="{{ route('admin.login') }}">
                @csrf

                <div>
                    <label class="block mb-1 text-green-300 font-semibold">Email</label>
                    <input type="email" name="email"
                        placeholder="admin@example.com"
                        class="input input-bordered w-full bg-gray-900/40 border-green-600 text-white focus:border-green-400" required />
                </div>

                <div>
                    <label class="block mb-1 text-green-300 font-semibold">Contraseña</label>
                    <input type="password" name="password"
                        placeholder="Ingrese su contraseña"
                        class="input input-bordered w-full bg-gray-900/40 border-green-600 text-white focus:border-green-400" required />
                </div>

                <button type="submit"
                   class="btn w-full bg-green-600 hover:bg-green-700 text-white py-3 text-lg rounded-full shadow-lg">
                    Entrar
                </button>

            </form>

            <div class="divider text-green-300 mt-8">Sistema Nacional de Estadios Deportivos</div>

        </div>
    </div>

</body>
</html>
