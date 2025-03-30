<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Hotel Inventory') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        
        <!-- Alpine.js -->
        <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

        <!-- Custom Styles -->
        <style>
            .btn-primary {
                background: linear-gradient(90deg, #4F46E5, #7C3AED);
                color: white;
                padding: 0.75rem 1.5rem;
                border-radius: 0.5rem;
                transition: all 0.3s ease;
            }
            .btn-primary:hover {
                transform: translateY(-2px);
                box-shadow: 0 4px 12px rgba(79, 70, 229, 0.3);
            }
            .btn-secondary {
                background: #6B7280;
                color: white;
                padding: 0.75rem 1.5rem;
                border-radius: 0.5rem;
                transition: all 0.3s ease;
            }
            .btn-secondary:hover {
                background: #4B5563;
                transform: translateY(-2px);
            }
            .card {
                background: white;
                border-radius: 0.75rem;
                box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
                transition: all 0.3s ease;
            }
            .card:hover {
                box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
            }
            .table-header {
                background: #F9FAFB;
                border-bottom: 2px solid #E5E7EB;
            }
            .input-field {
                border: 1px solid #D1D5DB;
                border-radius: 0.5rem;
                padding: 0.75rem;
                transition: all 0.3s ease;
            }
            .input-field:focus {
                border-color: #4F46E5;
                box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.2);
            }
        </style>
    </head>
    <body class="font-sans antialiased bg-gray-100 dark:bg-gray-900">
        <div class="min-h-screen flex">
            <!-- Sidebar -->
            @if (auth()->check())
                @include('components.layout.sidebar')
            @endif

            <!-- Main Content -->
            <div class="flex-1 ml-64">
                @yield('header')
                @yield('content')
            </div>
        </div>
    </body>
</html>