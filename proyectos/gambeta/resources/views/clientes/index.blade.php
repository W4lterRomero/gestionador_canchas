@extends('components.layouts.app')

@section('content')

<section class="container mx-auto px-6 py-10">

    <h1 class="text-3xl font-bold text-white mb-8">
        Clientes Frecuentes
    </h1>

    @livewire('clientes-manager')

</section>

@endsection
