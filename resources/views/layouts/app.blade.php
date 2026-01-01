{{-- resources/views/layouts/app.blade.php --}}
    <!DOCTYPE html>
<html lang="uz">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'AlifShop - O\'zbekistondagi eng yirik onlayn do\'kon')</title>

    {{-- Tailwind CSS CDN --}}
    <script src="https://cdn.tailwindcss.com"></script>

    {{-- Tailwind Config --}}
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#0d6efd',
                        danger: '#dc3545',
                        success: '#198754',
                    }
                }
            }
        }
    </script>

    {{-- Font Awesome --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    {{-- Custom CSS --}}
    <style>
        /* Line clamp */
        .line-clamp-2 {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        /* Smooth transitions */
        * {
            transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
        }

        /* Container */
        .container {
            width: 100%;
            margin-left: auto;
            margin-right: auto;
            padding-left: 1rem;
            padding-right: 1rem;
        }

        @media (min-width: 640px) {
            .container { max-width: 640px; }
        }

        @media (min-width: 768px) {
            .container { max-width: 768px; }
        }

        @media (min-width: 1024px) {
            .container { max-width: 1024px; }
        }

        @media (min-width: 1280px) {
            .container { max-width: 1280px; }
        }

        @media (min-width: 1536px) {
            .container { max-width: 1536px; }
        }
    </style>

    @stack('styles')
</head>
<body class="bg-gray-50">
<x-header />

<main>
    @yield('content')
</main>

<x-footer />

@stack('scripts')
</body>
</html>
