<!DOCTYPE html>
<html lang="ru" class="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="{{ Vite::asset('resources/img/logo.svg') }}" type="image/svg+xml">
    <title>{{ $title ?? 'Вход — DeployTask' }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="bg-zinc-950 text-white antialiased min-h-screen flex items-center justify-center p-4 selection:bg-red-500/30 selection:text-red-200">
    {{ $slot }}

    @livewireScripts
</body>
</html>
