<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $siteSetting->site_name ?? 'PIAI FUTSAL FITNESS' }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,600,900" rel="stylesheet" />
    <style>
        body { font-family: 'Instrument Sans', sans-serif; background-color: #0f0d0d; }
    </style>
</head>
<body class="text-gray-100">

    @include('landing.navbar')
    @include('landing.home')
    @include('landing.kesehatan')
    @include('landing.fitur')
    @include('landing.ulasan')
    @include('landing.tentang')

    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
</body>
</html>