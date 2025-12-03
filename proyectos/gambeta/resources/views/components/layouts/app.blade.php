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

        <!-- Flatpickr CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

    <!-- Tema  -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/themes/material_green.css">

    <!-- Flatpickr JS -->
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

</head>

<body class="text-gray-900 min-h-screen flex flex-col  bg-gradient-to-b from-[#063b2b] via-[#0b5c41] to-[#167a52]">    
    <!-- NAVBAR -->
    @include('components.navbar')

    <!-- CONTENIDO -->
    <main class="flex-grow">
        @yield('content')
        {{ $slot ?? '' }}
    </main>

<!-- FOOTER -->
<footer class="mt-16 text-white py-6
    bg-gradient-to-r from-[#01150f] via-[#022019] to-[#01150f]">

    <div class="container mx-auto px-6 text-center">

        <p class="text-sm opacity-90 font-semibold">
            &copy; 2025 Sistema Nacional de Estadios Deportivos
        </p>

        <div class="w-24 h-[1px] bg-white/20 mx-auto my-4"></div>

        <p class="text-xs opacity-70 tracking-wide uppercase">
            Todos los derechos reservados
        </p>

    </div>
</footer>



    <!-- SCRIPTS LIVEWIRE -->
    @livewireScripts

</body>
</html>
