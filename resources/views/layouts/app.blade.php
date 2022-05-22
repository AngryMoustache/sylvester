<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Sylvester</title>
        <script src="https://cdn.tailwindcss.com"></script>
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        @livewireStyles
    </head>
    <body class="bg-slate-100">
        <div class="flex">
            <div class="fixed w-80 h-screen bg-white p-4 border-r">
                <ul>
                    <li><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li><a href="{{ route('dashboard.filters') }}">Filters</a></li>
                </ul>
            </div>

            <div class="w-full ml-80 p-4">
                {{ $slot }}
            </div>
        </div>
        @livewireScripts
    </body>
</html>
