<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Sistema Nacional de Áreas Deportivas</title>
    <link rel="icon" href="{{ asset('images/logo.png') }}" type="image/png">

    @livewireStyles

    <!-- TailwindCSS -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- DaisyUI -->
    <link href="https://cdn.jsdelivr.net/npm/daisyui@4.10.1/dist/full.css" rel="stylesheet" />

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- FontAwesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <!-- Archivo CSS extra -->
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">

</head>

<body class="bg-gray-100 text-gray-900 min-h-screen flex flex-col">

    <!-- NAVBAR -->
    @include('components.navbar')

    <!-- CONTENIDO -->
    <main class="flex-grow">
        @yield('content')
        {{ $slot ?? '' }}
    </main>

    <!-- FOOTER -->
    <footer class="mt-16 bg-green-700 text-white text-center py-4">
        &copy; 2025 Sistema Nacional de Estadios Deportivos — Todos los derechos reservados
    </footer>

    <!-- SCRIPTS LIVEWIRE -->
    @livewireScripts

</body>
</html>
