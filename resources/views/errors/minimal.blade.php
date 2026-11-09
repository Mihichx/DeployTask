<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>@yield('title')</title>

    <style>
        /*! normalize.css v8.0.1 | MIT License | ://github.com */
        html {
            line-height: 1.15;
            -webkit-text-size-adjust: 100%
        }

        body {
            margin: 0;
            background-color: #0a0a0a !important; /* Глубокий черный фон */
            color: #ef4444 !important;            /* Основной ярко-красный цвет */
        }

        a {
            background-color: transparent
        }

        code {
            font-family: monospace, monospace;
            font-size: 1em
        }

        [hidden] {
            display: none
        }

        html {
            font-family: system-ui, -apple-system, BlinkMacSystemFont, Segoe UI, Roboto, Helvetica Neue, Arial, Noto Sans, sans-serif, Apple Color Emoji, Segoe UI Emoji, Segoe UI Symbol, Noto Color Emoji;
            line-height: 1.5
        }

        *,
        :after,
        :before {
            box-sizing: border-box;
            border: 0 solid #ffffff
        }

        a {
            color: inherit;
            text-decoration: inherit
        }

        code {
            font-family: Menlo, Monaco, Consolas, Liberation Mono, Courier New, monospace
        }

        svg,
        video {
            display: block;
            vertical-align: middle
        }

        .bg-white {
            background-color: #111111 !important;
        }

        .bg-gray-100 {
            background-color: #0a0a0a !important; /* Глубокий черный фон */
        }

        /* ЧИСТО БЕЛАЯ РАЗДЕЛИТЕЛЬНАЯ ЛИНИЯ (ПЕРЕКЛАДИНА) */
        .border-gray-400 {
            border-color: #ffffff !important;
            border-right-width: 1px !important;
        }

        .border-t {
            border-top-width: 1px
        }

        .border-r {
            border-right-width: 1px
        }

        .flex {
            display: flex
        }

        .grid {
            display: grid
        }

        .hidden {
            display: none
        }

        .items-center {
            align-items: center
        }

        .justify-center {
            justify-content: center
        }

        .font-semibold {
            font-weight: 600
        }

        .h-5 {
            height: 1.25rem
        }

        .h-8 {
            height: 2rem
        }

        .h-16 {
            height: 4rem
        }

        .text-sm {
            font-size: .875rem
        }

        .text-lg {
            font-size: 1.125rem
        }

        .leading-7 {
            line-height: 1.75rem
        }

        .mx-auto {
            margin-left: auto;
            margin-right: auto
        }

        .ml-1 {
            margin-left: .25rem
        }

        .mt-2 {
            margin-top: .5rem
        }

        .mr-2 {
            margin-right: .5rem
        }

        .ml-2 {
            margin-left: .5rem
        }

        .mt-4 {
            margin-top: 1rem
        }

        .ml-4 {
            margin-left: 1rem
        }

        .mt-8 {
            margin-top: 2rem
        }

        .ml-12 {
            margin-left: 3rem
        }

        .-mt-px {
            margin-top: -1px
        }

        .max-w-xl {
            max-width: 36rem
        }

        .max-w-6xl {
            max-width: 72rem
        }

        .min-h-screen {
            min-height: 100vh
        }

        .overflow-hidden {
            overflow: hidden
        }

        .p-6 {
            padding: 1.5rem
        }

        .py-4 {
            padding-top: 1rem;
            padding-bottom: 1rem
        }

        .px-4 {
            padding-left: 1rem;
            padding-right: 1rem
        }

        .px-6 {
            padding-left: 1.5rem;
            padding-right: 1.5rem
        }

        .pt-8 {
            padding-top: 2rem
        }

        .fixed {
            position: fixed
        }

        .relative {
            position: relative
        }

        .top-0 {
            top: 0
        }

        .right-0 {
            right: 0
        }

        .shadow {
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.9)
        }

        .text-center {
            text-align: center
        }

        /* Ярко-красный текст для цифр и сообщений */
        .text-gray-200 { color: #ef4444 !important; }
        .text-gray-300 { color: #ef4444 !important; }
        .text-gray-400 { color: #ef4444 !important; }
        .text-gray-500 { color: #ef4444 !important; } /* Текст сообщения */
        .text-gray-600 { color: #ef4444 !important; }
        .text-gray-700 { color: #ef4444 !important; }
        .text-gray-900 { color: #ef4444 !important; } /* ЦИФРЫ (404) */

        .uppercase {
            text-transform: uppercase
        }

        .underline {
            text-decoration: underline
        }

        .antialiased {
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale
        }

        .tracking-wider {
            letter-spacing: .05em
        }

        .w-5 {
            width: 1.25rem
        }

        .w-8 {
            width: 2rem
        }

        .w-auto {
            width: auto
        }

        .grid-cols-1 {
            grid-template-columns: repeat(1, minmax(0, 1fr))
        }
    </style>
</head>

<body class="antialiased">
    <div class="relative flex items-center justify-center min-h-screen bg-gray-100 items-top sm:items-center sm:pt-0">
        <div class="max-w-xl mx-auto sm:px-6 lg:px-8">
            <div class="flex items-center pt-8 sm:justify-start sm:pt-0">
                <!-- Красные цифры -->
                <div class="px-4 text-lg text-gray-900 border-r border-gray-400 tracking-wider font-mono font-bold">
                    @yield('code')
                </div>

                <!-- Красный текст -->
                <div class="ml-4 text-lg text-gray-500 uppercase tracking-wider font-mono">
                    @yield('message')
                </div>
            </div>
        </div>
    </div>
</body>

</html>
