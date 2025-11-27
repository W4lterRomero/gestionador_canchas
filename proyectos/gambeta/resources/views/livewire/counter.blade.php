<div class="min-h-screen flex items-center justify-center">
    <div class="bg-white p-8 rounded-lg shadow-lg max-w-md w-full">
        <h1 class="text-3xl font-bold mb-6 text-center text-gray-800">
            Contador Livewire
        </h1>

        <div class="text-center mb-6">
            <div class="text-6xl font-bold text-blue-600">
                {{ $count }}
            </div>
            <p class="text-gray-600 mt-2">Contador actual</p>
        </div>

        <div class="flex gap-4">
            <button
                wire:click="decrement"
                class="flex-1 bg-red-500 hover:bg-red-600 text-white font-bold py-3 px-6 rounded-lg transition"
            >
                - Decrementar
            </button>

            <button
                wire:click="increment"
                class="flex-1 bg-green-500 hover:bg-green-600 text-white font-bold py-3 px-6 rounded-lg transition"
            >
                + Incrementar
            </button>
        </div>

        <div class="mt-6 text-center">
            <button
                wire:click="$set('count', 0)"
                class="text-gray-600 hover:text-gray-800 underline"
            >
                Resetear a 0
            </button>
        </div>
    </div>
</div>
