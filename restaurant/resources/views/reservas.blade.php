<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight text-center">
            <h1 class="text-4xl font-bold text-gray-900 mb-8 text-center">Reservas del Restaurante</h1>
        </h2>
    </x-slot>

    <div class="flex justify-center px-4 sm:px-6 lg:px-8">
        <div class="mt-8 w-full max-w-full grid grid-cols-1 md:grid-cols-3 gap-8">

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

            <!-- Reservas Entrantes -->
            <div class="bg-white shadow-lg rounded-lg p-6 w-full mb-4">
                <h2 class="font-semibold text-gray-900 text-2xl mb-4 text-center">Reservas Entrantes</h2>
                <ul id="reservasEntrantes" class="space-y-4">
                    @foreach ($reservasEntrantes as $reserva)
                        <li id="reserva-{{ $reserva->id }}" class="bg-gray-50 p-4 rounded-lg shadow-md flex justify-between items-center">

                            <div class="flex-grow text-center">
                                <h3 class="font-medium text-gray-900">{{ $reserva->nombre_cliente }}</h3>
                                <p class="text-sm text-gray-500">Número de personas: {{ $reserva->numero_personas }}</p>
                                <p class="text-sm text-gray-500">Fecha de reserva: {{ $reserva->fecha_reserva }}</p>
                            </div>
                            <button onclick="cambiarEstado('{{ $reserva->id }}', 'siguiente')" class="text-blue-500 hover:text-blue-700 flex-shrink-0">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                </svg>
                            </button>
                        </li>
                    @endforeach
                </ul>
            </div>

            <!-- Reservas Inminentes -->
            <div class="bg-white shadow-lg rounded-lg p-6 w-full mb-4">
                <h2 class="font-semibold text-gray-900 text-2xl mb-4 text-center">Reservas Inminentes</h2>
                <ul id="reservasInminentes" class="space-y-4">
                    @foreach ($reservasInminentes as $reserva)
                        <li id="reserva-{{ $reserva->id }}" class="bg-gray-50 p-4 rounded-lg shadow-md flex justify-between items-center">
                            <button onclick="cambiarEstado('{{ $reserva->id }}', 'anterior')" class="text-blue-500 hover:text-blue-700 flex-shrink-0">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                                </svg>
                            </button>
                            <div class="flex-grow text-center">
                                <h3 class="font-medium text-gray-900">{{ $reserva->nombre_cliente }}</h3>
                                <p class="text-sm text-gray-500">Número de personas: {{ $reserva->numero_personas }}</p>
                                <p class="text-sm text-gray-500">Fecha de reserva: {{ $reserva->fecha_reserva }}</p>
                            </div>
                            <button onclick="cambiarEstado('{{ $reserva->id }}', 'siguiente')" class="text-blue-500 hover:text-blue-700 flex-shrink-0">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                </svg>
                            </button>
                        </li>
                    @endforeach
                </ul>
            </div>

            <!-- Reservas Pendientes -->
            <div class="bg-white shadow-lg rounded-lg p-6 w-full mb-4">
                <h2 class="font-semibold text-gray-900 text-2xl mb-4 text-center">Reservas Pendientes</h2>
                <ul id="reservasPendientes" class="space-y-4">
                    @foreach ($reservasPendientes as $reserva)
                        <li id="reserva-{{ $reserva->id }}" class="bg-gray-50 p-4 rounded-lg shadow-md flex justify-between items-center">
                            <button onclick="cambiarEstado('{{ $reserva->id }}', 'anterior')" class="text-blue-500 hover:text-blue-700 flex-shrink-0">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                                </svg>
                            </button>
                            <div class="flex-grow text-center">
                                <h3 class="font-medium text-gray-900">{{ $reserva->nombre_cliente }}</h3>
                                <p class="text-sm text-gray-500">Número de personas: {{ $reserva->numero_personas }}</p>
                                <p class="text-sm text-gray-500">Fecha de reserva: {{ $reserva->fecha_reserva }}</p>
                            </div>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>

</x-app-layout>
<script>
    function cambiarEstado(reservaId, direccion) {
        fetch(`/reservas/${reservaId}/mover`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ direccion })
        })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    actualizarVista(data.reserva);
                } else {
                    alert('Error al mover la reserva');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error al mover la reserva');
            });
    }

    function actualizarVista(reserva) {
        const reservaElement = document.getElementById(`reserva-${reserva.id}`);
        if (reservaElement) {
            reservaElement.remove();
        }


        let nuevoEstado = '';
        if (reserva.estado === 'inminente') {
            nuevoEstado = 'reservasInminentes';
        } else if (reserva.estado === 'pendiente') {
            nuevoEstado = 'reservasPendientes';
        } else {
            nuevoEstado = 'reservasEntrantes';
        }

        const ul = document.getElementById(nuevoEstado);
        const li = document.createElement('li');
        li.id = `reserva-${reserva.id}`;
        li.className = 'bg-gray-50 p-4 rounded-lg shadow-md flex justify-between items-center';
        li.innerHTML = `
            ${reserva.estado !== 'entrante' ? `<button onclick="cambiarEstado('${reserva.id}', 'anterior')" class="text-blue-500 hover:text-blue-700">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
            </button>` : ''}
            <div class="flex-grow text-center">
                <h3 class="font-medium text-gray-900">${reserva.nombre_cliente}</h3>
                <p class="text-sm text-gray-500">Número de personas: ${reserva.numero_personas}</p>
                <p class="text-sm text-gray-500">Fecha de reserva: ${reserva.fecha_reserva}</p>
            </div>
            ${reserva.estado !== 'pendiente' ? `<button onclick="cambiarEstado('${reserva.id}', 'siguiente')" class="text-blue-500 hover:text-blue-700">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
            </button>` : ''}
        `;
        ul.appendChild(li);
    }
</script>
