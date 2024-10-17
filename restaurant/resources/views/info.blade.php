<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite('resources/css/app.css')
</head>
<body>
<header class="bg-white dark:bg-gray-900">
    <x-app-layout>
        <x-slot name="header">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight text-center">
                <h1 class="text-3xl font-bold text-center mb-8">Treball fet per Oriol Mariné Sevilla</h1>

            </h2>
        </x-slot>



        <div class="container px-6 py-16 mx-auto">
            <div class="flex items-center justify-center lg:flex lg:justify-center">
                <div class="flex items-center justify-center w-full mt-6 lg:mt-0 lg:w-1/2">
                    <img class="w-full h-full max-w-md" src="https://merakiui.com/images/components/Email-campaign-bro.svg" alt="email illustration vector art">
                </div>
            </div>
        </div>

    </x-app-layout>


</header>
</body>
</html>
