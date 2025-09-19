<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? config('app.name', 'Binsiyon') }} - Yönetim Paneli</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased">
<div class="d-flex" style="min-height: 100vh;">
    @include('layouts.partials.sidebar')

    <div class="flex-grow-1 bg-light d-flex flex-column">
        @include('layouts.navigation')

        @if (isset($header))
            <header class="bg-white shadow-sm border-bottom">
                <div class="container-fluid py-3 px-4">
                    {{ $header }}
                </div>
            </header>
        @endif

        <main class="p-4 flex-grow-1">
            {{ $slot }}
        </main>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
