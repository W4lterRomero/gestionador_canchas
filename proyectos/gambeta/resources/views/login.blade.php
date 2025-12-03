<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema Nacional de Áreas Deportivas</title>

    <link rel="icon" href="{{ asset('images/logo.png') }}" type="image/png">

    {{-- CSS --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            height: 100vh;
            margin: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            background-color: #1a1a1a;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        /* Fondo */
        .bg-image {
            position: fixed;
            top: -20px;
            left: -20px;
            width: calc(100% + 40px);
            height: calc(100% + 40px);
            background-image: url('{{ asset("images/Fondo.jpg") }}');
            background-size: cover;
            background-position: center;
            filter: blur(10px); /* Efecto de blur solicitado */
            z-index: -1;
        }

        /* Overlay */
        .overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.4);
            z-index: 0;
        }

        .login-card {
            position: relative;
            z-index: 1;
            background: rgba(255, 255, 255, 0.1); /* Estilo */
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 1.5rem;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
            width: 100%;
            max-width: 420px;
            padding: 3rem 2.5rem;
            color: white;
        }

        .login-logo {
            width: 140px;
            height: auto;
            display: block;
            margin: 0 auto 2rem;
            filter: brightness(0) invert(1) drop-shadow(0 0 8px rgba(255,255,255,0.5)); /* Logo */
            transition: transform 0.3s ease;
        }

        .login-logo:hover {
            transform: scale(1.05);
        }

        .form-control {
            background: rgba(255, 255, 255, 0.1) !important;
            border: 1px solid rgba(255, 255, 255, 0.2) !important;
            color: white !important;
            border-radius: 0.75rem;
            height: 3.5rem;
        }

        .form-control:focus {
            background: rgba(255, 255, 255, 0.15) !important;
            border-color: rgba(255, 255, 255, 0.5) !important;
            color: white !important;
            box-shadow: 0 0 0 0.25rem rgba(255, 255, 255, 0.1) !important;
        }

        /* Fix label */
        .form-floating > .form-control:focus ~ label::after,
        .form-floating > .form-control:not(:placeholder-shown) ~ label::after {
            background-color: transparent !important;
        }

        .form-control::placeholder {
            color: transparent;
        }

        .form-floating > label {
            color: rgba(255, 255, 255, 0.8);
            padding-left: 1rem;
            z-index: 2;
        }

        .form-floating > .form-control:focus ~ label,
        .form-floating > .form-control:not(:placeholder-shown) ~ label {
            color: rgba(255, 255, 255, 0.9) !important;
            transform: scale(0.85) translateY(-0.75rem) translateX(0.15rem);
            background-color: transparent !important;
        }

        /* Fix autocompletado */
        input:-webkit-autofill,
        input:-webkit-autofill:hover,
        input:-webkit-autofill:focus,
        input:-webkit-autofill:active{
            -webkit-box-shadow: 0 0 0 30px rgba(255, 255, 255, 0.1) inset !important;
            -webkit-text-fill-color: white !important;
            transition: background-color 5000s ease-in-out 0s;
        }

        .btn-custom {
            background: linear-gradient(135deg, #3c7a4eff, #2fb341ff); /* Gradiente */
            border: none;
            color: white;
            font-weight: 700;
            letter-spacing: 1px;
            text-transform: uppercase;
            padding: 1rem;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
        }

        .btn-custom:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(150, 201, 61, 0.4);
            color: white;
            filter: brightness(1.1);
        }

        .alert-danger {
            background-color: rgba(220, 53, 69, 0.8);
            border: 1px solid rgba(220, 53, 69, 0.5);
            color: white;
            backdrop-filter: blur(5px);
        }
    </style>
</head>

<body>

    <div class="bg-image"></div>
    <div class="overlay"></div>

    <div class="login-card">
        {{-- Logo --}}
        <img src="{{ asset('images/logo.png') }}" alt="Logo" class="login-logo">

        {{-- Título --}}
        <h3 class="text-center mb-4 fw-bold" style="letter-spacing: 1px;">BIENVENIDO</h3>

        {{-- Errores --}}
        @if ($errors->any())
        <div class="alert alert-danger mb-4 rounded-3" role="alert">
            <ul class="mb-0 ps-3">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        {{-- Formulario --}}
        <form method="POST" action="{{ route('admin.login') }}">
            @csrf

            {{-- Email --}}
            <div class="form-floating mb-3">
                <input type="email" class="form-control" id="email" name="email" placeholder="name@example.com" required>
                <label for="email">Correo Electrónico</label>
            </div>

            {{-- Contraseña --}}
            <div class="form-floating mb-4">
                <input type="password" class="form-control" id="password" name="password" placeholder="Password" required>
                <label for="password">Contraseña</label>
            </div>

            {{-- Botón --}}
            <div class="d-grid">
                <button type="submit" class="btn btn-custom btn-lg rounded-pill">
                    INGRESAR
                </button>
            </div>

        </form>

        <div class="text-center mt-4 text-white-50 small">
            &copy; 2025 Sistema Nacional de Estadios Deportivos
        </div>
    </div>

    {{-- JS --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
