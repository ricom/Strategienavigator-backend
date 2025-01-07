<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Lokales Bootstrap CSS einbinden -->
        <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">
        <!-- Lokales Bootstrap JavaScript einbinden -->
        <script src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>


    </head>
    <body class="bg-light text-dark">
    <div class="d-flex flex-column justify-content-center align-items-center min-vh-100 pt-3 pt-sm-0 bg-light">
        <div>
            <a href="/">
                <x-application-logo class="w-20 h-20 fill-current text-gray-500" />
            </a>
        </div>

        <div class="w-100 mt-3 px-3 py-4 bg-white shadow-sm overflow-hidden rounded-lg" style="max-width: 24rem;">
            {{ $slot }}
        </div>
    </div>
    </body
</html>
