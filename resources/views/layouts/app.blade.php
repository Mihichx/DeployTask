<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="{{ Vite::asset('resources/img/logo.svg') }}" type="image/svg+xml">
    <title>{{ $title ?? 'DeployTask' }}</title>

    @include('partials.focus-init')

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>

<body x-data="focusLayout" @keydown.window="$store.focus.handleKeydown($event)"
    class="bg-zinc-950 text-white antialiased min-h-screen flex selection:bg-red-500/30 selection:text-red-200">

    <x-layouts.sidebar />

    <main class="flex-1 min-w-0 p-6">
        {{ $slot }}
    </main>

    @livewireScripts
</body>

</html>
