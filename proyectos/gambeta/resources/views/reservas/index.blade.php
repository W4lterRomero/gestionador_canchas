@extends('components.layouts.app')

@section('content')

<section class="container mx-auto px-6 py-10">

    <h1 class="text-3xl font-bold text-green-700 ">
        Crear nueva reserva
    </h1>

    @livewire('reserva-flow')

</section>

@endsection
