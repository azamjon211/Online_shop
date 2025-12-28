{{-- resources/views/layouts/app.blade.php --}}
    <!DOCTYPE html>
<html lang="uz">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Bosh sahifa') - AlifShop</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    @stack('styles')
</head>
<body class="bg-gray-50">
{{-- Header --}}
@include('components.header')

{{-- Main Content --}}
<main>
    @yield('content')
</main>

{{-- Footer --}}
@include('components.footer')

@stack('scripts')
</body>
</html>
