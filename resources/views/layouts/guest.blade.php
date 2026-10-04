<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'GMAO Elevate') }}</title>

        <!-- Scripts & Estilos -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-gray-900 antialiased bg-slate-100">
        <!-- Contenedor general centrado horizontal y verticalmente -->
        <div class="min-h-screen flex flex-col justify-center items-center p-4">
            {{ $slot }}
        </div>
    </body>
</html>
