<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'InvoiceManagement') }}</title>

        <!-- Favicon -->
        <link rel="icon" type="image/png" href="{{ asset('icon.png') }}">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        
        <style>
            body {
                font-family: 'Inter', sans-serif;
            }
            .glass-effect {
                background: rgba(255, 255, 255, 0.95);
                backdrop-filter: blur(10px);
                -webkit-backdrop-filter: blur(10px);
            }
            .bg-animated {
                background: linear-gradient(-45deg, #ee7752, #e73c7e, #23a6d5, #23d5ab);
                background-size: 400% 400%;
                animation: gradient 15s ease infinite;
            }
            @keyframes gradient {
                0% { background-position: 0% 50%; }
                50% { background-position: 100% 50%; }
                100% { background-position: 0% 50%; }
            }
        </style>
    </head>
    <body class="font-sans text-gray-900 antialiased">
        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-animated">
            <div class="w-full sm:max-w-md mt-6 px-8 py-8 glass-effect shadow-2xl overflow-hidden sm:rounded-2xl transform transition-all hover:scale-[1.01] duration-300">
                <div class="flex justify-center mb-6">
                    <a href="/" class="transition-transform duration-300 hover:scale-110">
                        <img src="{{ asset('icon.png') }}" alt="Application Logo" class="w-24 h-24" />
                    </a>
                </div>
                {{ $slot }}
            </div>
            
            <div class="mt-8 px-6 py-2 glass-effect rounded-full shadow-lg text-gray-800 text-sm flex items-center justify-center gap-2 transform transition-transform hover:scale-105 duration-300">
                <img src="{{ asset('logo_tp.png') }}" alt="Logo" class="h-6 w-auto" />
                <span class="font-semibold">&copy; {{ date('Y') }} NerdTech Labs. All rights reserved.</span>
            </div>
        </div>
    </body>
</html>
