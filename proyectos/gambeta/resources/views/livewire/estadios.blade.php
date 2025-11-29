<div>

    <!-- FILTROS LIVEWIRE -->
    <section class="container mx-auto mt-10 px-6">

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">

            <!-- BUSCADOR -->
            <input wire:model.live="search"
                class="mary-input mary-input-bordered bg-white border-green-400/30 text-gray-700 h-14 text-lg"
                placeholder="Buscar estadio por nombre...">

            <!-- SELECT -->
            <select wire:model.live="tipo"
                class="mary-select mary-select-bordered bg-white border-green-400/30 text-gray-700 h-14 text-lg">
                <option value="">Filtrar por tipo</option>
                <option>Fútbol 5</option>
                <option>Fútbol rápido</option>
                <option>Indoor</option>
            </select>

        </div>

        <!-- Cantidad -->
        <p class="mt-4 text-gray-600">{{ $this->filtrados->count() }} estadios encontrados</p>

    </section>


    <!-- TARJETAS -->
    <section class="container mx-auto mt-8 px-6 grid grid-cols-1 md:grid-cols-3 gap-8">

        @foreach($this->filtrados as $e)

            <div class="bg-white rounded-2xl shadow-lg overflow-hidden hover:shadow-2xl hover:-translate-y-1 transition">

                <img src="{{ asset($e['imagen']) }}" class="h-52 w-full object-cover">

                <div class="p-5">
                    <h3 class="text-xl font-bold text-green-700">{{ $e['nombre'] }}</h3>

                    <p class="text-sm text-gray-600 mt-1">
                        {{ $e['ciudad'] }}, Zona Oriental
                    </p>

                    <p class="mt-3 text-gray-700 text-sm leading-relaxed">
                        Tipo: <b>{{ $e['tipo'] }}</b><br>
                        Precio por hora: <b>${{ $e['precio'] }}</b>
                    </p>

                <a href="{{ route('estadios.detalles') }}"
                class="mary-btn bg-green-600 hover:bg-green-500 text-white w-full mt-4 rounded-full text-center block">
                    Ver Detalles
                </a>
                </div>

            </div>

        @endforeach

    </section>

</div>
