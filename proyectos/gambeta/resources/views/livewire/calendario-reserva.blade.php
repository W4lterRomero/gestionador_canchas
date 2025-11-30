<div class="space-y-5">

    <div class="flex items-center justify-between px-3">
        <button wire:click="cambiarMes(-1)" class="text-gray-600 hover:text-green-600 text-xl">
            ‹
        </button>

        <div class="text-lg font-bold text-green-700">
            {{ \Carbon\Carbon::create($anio, $mes)->locale('es')->isoFormat('MMMM YYYY') }}
        </div>

        <button wire:click="cambiarMes(1)" class="text-gray-600 hover:text-green-600 text-xl">
            ›
        </button>
    </div>

    <div class="grid grid-cols-7 text-center text-sm font-semibold text-gray-500">
        <div>Dom</div><div>Lun</div><div>Mar</div>
        <div>Mié</div><div>Jue</div><div>Vie</div><div>Sáb</div>
    </div>

    <div class="grid grid-cols-7 gap-1 text-center">

@php
    use Carbon\Carbon;

    $primerDiaMes = Carbon::create($anio, $mes, 1);
    $inicioSemana = $primerDiaMes->copy()->startOfWeek(Carbon::SUNDAY);
    $finMes       = $primerDiaMes->copy()->endOfMonth();
    $finSemana    = $finMes->copy()->endOfWeek(Carbon::SATURDAY);

    $hoy = Carbon::today();
@endphp

@for ($dia = $inicioSemana->copy(); $dia <= $finSemana; $dia->addDay())

    @php
        $esMesActual  = $dia->month == $mes;
        $fechaStr     = $dia->format('Y-m-d');
        $seleccionado = $fechaStr == $fecha;

        $esPasado = $dia < $hoy;
    @endphp

    @if($esMesActual)
        <div class="py-2 rounded-lg text-center
            @if($esPasado)
                bg-gray-200 text-gray-400 cursor-not-allowed opacity-60
            @elseif($seleccionado)
                bg-green-600 text-white font-bold
            @else
                hover:bg-green-100 cursor-pointer
            @endif"
            @if(!$esPasado)
                wire:click="seleccionarDia({{ $dia->day }})"
            @endif
        >
            {{ $dia->day }}
        </div>
    @else
        <div></div>
    @endif

@endfor

    </div>

    <div class="grid grid-cols-3 gap-2 max-h-48 overflow-y-auto p-2 bg-white rounded-xl border border-green-200">

        @foreach ($horasDisponibles as $h)
            <button 
                wire:click="seleccionarHora('{{ $h['hora'] }}')"
                class="px-3 py-2 rounded-lg text-sm font-semibold
                    @if($h['ocupada'])
                        bg-red-200 text-red-700 cursor-not-allowed opacity-50
                    @elseif($hora === $h['hora'])
                        bg-green-600 text-white
                    @else
                        bg-gray-100 text-gray-700 border border-green-100 hover:bg-green-50 hover:text-green-700
                    @endif"
                @if($h['ocupada']) disabled @endif
            >
                {{ $h['hora'] }}
            </button>
        @endforeach

    </div>
</div>
