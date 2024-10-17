<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight text-center">
            {{ __('Reservas del restaurante') }}
        </h2>
    </x-slot>

    <div class="px-4 sm:px-6 lg:px-8">


        <div class="mt-8 grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="bg-white shadow sm:rounded-lg p-4">
                <h2 class="font-semibold text-gray-900 text-lg mb-4">Reservas pendientes</h2>
                <ul class="space-y-4">
                    <li class="bg-gray-50 p-4 rounded-lg shadow">
                        <h3 class="font-medium text-gray-900">Task Title</h3>
                        <p class="text-sm text-gray-500">Description of the task goes here.</p>
                    </li>
                </ul>
            </div>

            <div class="bg-white shadow sm:rounded-lg p-4">
                <h2 class="font-semibold text-gray-900 text-lg mb-4">Reservas en entrada inminente</h2>
                <ul class="space-y-4">
                    <li class="bg-yellow-50 p-4 rounded-lg shadow">
                        <h3 class="font-medium text-gray-900">Task Title</h3>
                        <p class="text-sm text-gray-500">Description of the task goes here.</p>
                    </li>
                </ul>
            </div>

            <div class="bg-white shadow sm:rounded-lg p-4">
                <h2 class="font-semibold text-gray-900 text-lg mb-4">Reservas entrantes</h2>
                <ul class="space-y-4">
                    <li class="bg-green-50 p-4 rounded-lg shadow">
                        <h3 class="font-medium text-gray-900">Task Title</h3>
                        <p class="text-sm text-gray-500">Description of the task goes here.</p>
                    </li>
                </ul>
            </div>
        </div>
    </div>


</x-app-layout>
