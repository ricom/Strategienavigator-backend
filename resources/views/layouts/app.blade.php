<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>

    <!-- Lokales Bootstrap CSS einbinden -->
    <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">
    <!-- Lokales Fontawesome -->
    <link rel="stylesheet" href="{{ asset('fonts/fontawesome/css/all.min.css') }}" />
    <!-- custom CSS -->
    <!-- <link href="{{ asset('css/custom.css') }}" rel="stylesheet"> -->
</head>
<body>
<div id="app" class="d-flex flex-column min-vh-100">
    @include('layouts.partials.navigation')

    <header>
        <!-- Navigation -->
            @yield('header')
        <!-- //Navigation// -->
    </header>

    <main>
        @yield('content')
    </main>

    <footer>
        <!-- Footer -->
        @include('layouts.partials.footer')
        <!-- //Footer// -->
    </footer>

    <!-- Lokales Bootstrap JavaScript einbinden -->
    <script src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{asset('js/custom.js')}}"></script>
    <script>
        // JavaScript to handle dropdown functionality
        document.querySelector('.dropdown-btn').addEventListener('click', function() {
            const dropdown = this.nextElementSibling;
            dropdown.classList.toggle('hidden');
        });
    </script>
</body>
</html>
