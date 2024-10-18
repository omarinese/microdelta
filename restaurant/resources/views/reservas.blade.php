<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight text-center">
            <h1 class="text-4xl font-bold text-gray-900 mb-8 text-center">Reservas del Restaurante</h1>
        </h2>
    </x-slot>

    <div class="flex justify-center px-4 sm:px-6 lg:px-8">
        <div class="mt-8 w-full max-w-full grid grid-cols-1 md:grid-cols-3 gap-8">

            <!-- Formulario de Importación -->
            <div class="col-span-1 md:col-span-3 mb-8">
                <form action="{{ route('reservas.importar') }}" method="POST" enctype="multipart/form-data" class="bg-white shadow-md rounded-lg p-6">
                    @csrf
                    <h2 class="font-semibold text-gray-900 text-2xl mb-4 text-center">Importar Reservas desde Excel</h2>
                    <div class="mb-4">
                        <input type="file" name="file" accept=".xlsx,.xls,.csv" class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg p-2.5" required>
                    </div>
                    <div class="flex justify-center">
                        <button type="submit" class="bg-blue-500 text-white font-semibold py-2 px-4 rounded-lg hover:bg-blue-600 transition duration-200">
                            Importar
                        </button>
                    </div>
                </form>
            </div>

            <!-- Reservas Pendientes -->
            <div class="bg-white shadow-lg rounded-lg p-6 w-full">
                <h2 class="font-semibold text-gray-900 text-2xl mb-4 text-center">Reservas Pendientes</h2>
                <ul id="reservasPendientes" class="space-y-4">
                    @forelse ($reservasPendientes as $reserva)
                        <li class="bg-gray-50 p-4 rounded-lg shadow-md transition-transform transform hover:scale-105">
                            <h3 class="font-medium text-gray-900">{{ $reserva->nombre_cliente }}</h3>
                            <p class="text-sm text-gray-500">Número de personas: {{ $reserva->numero_personas }}</p>
                            <p class="text-sm text-gray-500">Fecha de reserva: {{ $reserva->fecha_reserva instanceof \Carbon\Carbon ? $reserva->fecha_reserva->format('d/m/Y') : 'Fecha inválida' }}</p>
                        </li>
                    @empty
                        <li class="bg-gray-50 p-4 rounded-lg shadow-md">
                            <p class="text-sm text-gray-500 text-center">No hay reservas pendientes.</p>
                        </li>
                    @endforelse
                </ul>
            </div>

            <!-- Reservas Inminentes -->
            <div class="bg-white shadow-lg rounded-lg p-6 w-full">
                <h2 class="font-semibold text-gray-900 text-2xl mb-4 text-center">Reservas Inminentes</h2>
                <ul id="reservasInminentes" class="space-y-4">
                    @forelse ($reservasInminentes as $reserva)
                        <li class="bg-gray-50 p-4 rounded-lg shadow-md transition-transform transform hover:scale-105">
                            <h3 class="font-medium text-gray-900">{{ $reserva->nombre_cliente }}</h3>
                            <p class="text-sm text-gray-500">Número de personas: {{ $reserva->numero_personas }}</p>
                            <p class="text-sm text-gray-500">Fecha de reserva: {{ $reserva->fecha_reserva instanceof \Carbon\Carbon ? $reserva->fecha_reserva->format('d/m/Y') : 'Fecha inválida' }}</p>
                        </li>
                    @empty
                        <li class="bg-gray-50 p-4 rounded-lg shadow-md">
                            <p class="text-sm text-gray-500 text-center">No hay reservas inminentes.</p>
                        </li>
                    @endforelse
                </ul>
            </div>



            <!-- Reservas Entrantes -->
            <div class="bg-white shadow-lg rounded-lg p-6 w-full">
                <h2 class="font-semibold text-gray-900 text-2xl mb-4 text-center">Reservas Entrantes</h2>
                <ul id="reservasEntrantes" class="space-y-4">
                    @forelse ($reservasEntrantes as $reserva)
                        <li class="bg-gray-50 p-4 rounded-lg shadow-md transition-transform transform hover:scale-105">
                            <h3 class="font-medium text-gray-900">{{ $reserva->nombre_cliente }}</h3>
                            <p class="text-sm text-gray-500">Número de personas: {{ $reserva->numero_personas }}</p>
                            <p class="text-sm text-gray-500">Fecha de reserva: {{ $reserva->fecha_reserva instanceof \Carbon\Carbon ? $reserva->fecha_reserva->format('d/m/Y') : 'Fecha inválida' }}</p>
                        </li>
                    @empty
                        <li class="bg-gray-50 p-4 rounded-lg shadow-md">
                            <p class="text-sm text-gray-500 text-center">No hay reservas entrantes.</p>
                        </li>
                    @endforelse
                </ul>
            </div>



        </div>
    </div>
</x-app-layout>
